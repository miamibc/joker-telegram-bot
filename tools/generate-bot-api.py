#!/usr/bin/env python3
"""
Telegram Bot API generator for Joker

Reads Telegram Bot API documentation https://core.telegram.org/bots/api and generates:
- src/BotApi.php  trait with all API methods, except ones defined in src/Bot.php by hand
- src/Parser/*.php  parser classes for all types that can come from Telegram
  (reachable from Update and method results); existing classes keep their
  custom code and wrappers, only docblock and $wrapper list are updated

Usage (from project root):
    python3 tools/generate-bot-api.py                 # download documentation and generate
    python3 tools/generate-bot-api.py --html api.html # use saved documentation page
    python3 tools/generate-bot-api.py --dry-run       # only show what would be done
    python3 tools/generate-bot-api.py --json api.json # also save parsed documentation

Only Python 3 standard library is used.
"""

import argparse, html, json, os, re, sys, urllib.request

DOCS_URL = 'https://core.telegram.org/bots/api'


def download(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'joker-telegram-bot generator'})
    with urllib.request.urlopen(req, timeout=60) as res:
        return res.read().decode('utf-8')


def text(s):
    s = re.sub(r'<img[^>]*alt="([^"]*)"[^>]*>', r'\1', s)
    s = re.sub(r'<br\s*/?>', ' ', s)
    s = re.sub(r'<[^>]+>', '', s)
    return html.unescape(re.sub(r'\s+', ' ', s)).strip()


def parse(src):
    """Documentation page -> (version, list of methods and types)"""
    m = re.search(r'<strong>Bot API ([0-9.]+)</strong>', src)
    version = m.group(1) if m else ''
    body = src[src.find('id="dev_page_content"'):]
    parts = re.split(r'(<h[34][^>]*>.*?</h[34]>)', body, flags=re.S)
    items = []; section = None
    for i in range(1, len(parts), 2):
        head = parts[i]; content = parts[i + 1]
        name = text(head)
        if head.startswith('<h3'):
            section = name; continue
        if not re.match(r'^[A-Za-z]+$', name): continue
        if section in (None, 'Recent changes'): continue
        tbl = re.search(r'<table.*?</table>', content, re.S)
        pre = content[:tbl.start()] if tbl else content
        rows = []
        if tbl:
            for tr in re.findall(r'<tr>(.*?)</tr>', tbl.group(0), re.S)[1:]:
                rows.append([text(td) for td in re.findall(r'<td>(.*?)</td>', tr, re.S)])
        items.append({
            'name': name, 'section': section,
            'kind': 'method' if name[0].islower() else 'type',
            'desc': [text(p) for p in re.findall(r'<p>(.*?)</p>', pre, re.S)],
            'subtypes': [text(p) for p in re.findall(r'<li>(.*?)</li>', pre, re.S)],
            'fields': rows,
        })
    return version, items


ap = argparse.ArgumentParser(description='Generate Joker Bot API methods and parser classes from Telegram documentation')
ap.add_argument('--html', help='saved documentation page instead of downloading ' + DOCS_URL)
ap.add_argument('--json', help='save parsed documentation to this file')
ap.add_argument('--root', default=os.path.join(os.path.dirname(os.path.abspath(__file__)), '..'), help='project root')
ap.add_argument('--dry-run', action='store_true', help='do not write files')
args = ap.parse_args()

ROOT = os.path.abspath(args.root)
PARSER = os.path.join(ROOT, 'src/Parser')
DRY = args.dry_run

VERSION, api = parse(open(args.html, encoding='utf-8').read() if args.html else download(DOCS_URL))
if args.json:
    json.dump(api, open(args.json, 'w'), ensure_ascii=False, indent=1)
print('Bot API %s: %d methods, %d types' % (VERSION or '?', sum(x['kind'] == 'method' for x in api), sum(x['kind'] == 'type' for x in api)))

# author line is taken from an existing project file
AUTHOR = re.search(r'^ \* @author .*$', open(os.path.join(PARSER, 'Message.php')).read(), re.M).group(0)

types = {x['name']: x for x in api if x['kind'] == 'type'}
methods = [x for x in api if x['kind'] == 'method']
ALIAS = {'MaybeInaccessibleMessage': 'Message'}

def clean(s):
    return s.replace('*/', '* /').strip()

# ---- unions: merge subtype fields ----
unions = {}
for n, t in types.items():
    if n in ALIAS: continue
    subs = [s for s in t['subtypes'] if s in types and types[s]['fields']]
    if not t['fields'] and subs:
        unions[n] = subs
union_members = {s for subs in unions.values() for s in subs}

def fields_of(n):
    """list of (name, type, desc)"""
    if n in unions:
        merged = {}; order = []
        subs = unions[n]
        for s in subs:
            for f in types[s]['fields']:
                if f[0] not in merged:
                    merged[f[0]] = [f[1], f[2], [s]]; order.append(f[0])
                else:
                    merged[f[0]][2].append(s)
        out = []
        for k in order:
            typ, desc, where = merged[k]
            if len(where) < len(subs):
                desc = ', '.join(where) + ' only. ' + desc
            out.append((k, typ, desc))
        return out
    return [(f[0], f[1], f[2]) for f in types[n]['fields']]

PRIM = {'Integer': 'integer', 'String': 'string', 'Boolean': 'bool', 'True': 'bool', 'Float': 'float', 'Float number': 'float', 'Int': 'integer'}

def resolve(name):
    name = ALIAS.get(name, name)
    return name if name in types else None

def ftype(t):
    """-> (doc type, wrapper class or None)"""
    t = t.strip()
    if t.startswith('Array of Array of'): return 'array', None
    if t.startswith('Array of '):
        inner = t[len('Array of '):]
        if inner in PRIM: return PRIM[inner] + '[]', None
        r = resolve(inner)
        if r in union_members: r = None
        return (r + '[]', r) if r else ('array', None)
    if ' or ' in t: return 'mixed', None
    if t in PRIM: return PRIM[t], None
    r = resolve(t)
    if r in union_members: r = None
    return (r, r) if r else ('mixed', None)

# ---- method return types ----
def returns(m):
    s = ' '.join(m['desc'])
    sents = [x for x in re.split(r'(?<=\.)\s', s) if re.search(r'\breturn', x, re.I)]
    cls = None; arr = False; boolean = False; scalar = None
    for sent in sents:
        for mm in re.finditer(r'(Array of |array of )?\b([A-Z][A-Za-z]+)\b', sent):
            word = mm.group(2)
            if word == 'True': boolean = True; continue
            if word == 'String' and not cls: scalar = 'string'; continue
            if word == 'Integer' and not cls: scalar = 'integer'; continue
            r = resolve(word)
            if r and not cls:
                cls = r; arr = bool(mm.group(1))
        if not cls and re.search(r'array of Messages', sent, re.I): cls = 'Message'; arr = True
    return cls, arr, boolean, scalar

# ---- reachability ----
reach = set(); stack = ['Update']
for m in methods:
    c = returns(m)[0]
    if c: stack.append(c)
while stack:
    n = stack.pop()
    if n in reach or n not in types: continue
    reach.add(n)
    for f in fields_of(n):
        w = ftype(f[1])[1]
        if w: stack.append(w)
        # union-typed fields of a union class
        base = f[1].replace('Array of ', '').strip()
        if base in unions: stack.append(base)
for n in list(reach):
    for f in fields_of(n):
        base = f[1].replace('Array of ', '').strip()
        if base in unions: reach.add(base)
reach -= union_members
reach -= set(ALIAS)

# union-typed fields wrap into union class
def ftype2(t):
    doc, w = ftype(t)
    base = t.replace('Array of ', '').strip()
    if not w and base in unions and not t.startswith('Array of Array of'):
        arr = t.startswith('Array of ')
        return (base + ('[]' if arr else '')), base
    return doc, w

HEADER = "<?php\n/**\n * Telegram Bot API parser for Joker\n *\n * @package joker-telegram-bot\n" + AUTHOR + "\n */\n\nnamespace Joker\\Parser;\n"

def docblock(n, old_methods):
    t = types[n]
    lines = []
    for p in t['desc']: lines.append(clean(p))
    if n in unions:
        lines.append('Currently can be one of ' + ', '.join(unions[n]) + ' — all of them are parsed by this class.')
    lines.append('@see https://core.telegram.org/bots/api#' + n.lower())
    lines.append('')
    names = set()
    for k, typ, desc in fields_of(n):
        doc, _ = ftype2(typ)
        lines.append('@method %s %s() %s' % (doc, k, clean(desc)))
        names.add(k)
    for k, line in old_methods:
        if k not in names: lines.append(line)
    return '/**\n' + '\n'.join((' * ' + l) if l else ' *' for l in lines) + '\n */\n'

def wrapper_of(n):
    w = {}
    for k, typ, desc in fields_of(n):
        c = ftype2(typ)[1]
        if c: w[k] = c
    return w

def render_wrapper(w):
    return "  protected $wrapper = [\n" + ''.join("    '%s' => %s::class,\n" % (k, v) for k, v in w.items()) + "  ];"

created, updated = [], []
for n in sorted(reach):
    path = os.path.join(PARSER, n + '.php')
    w = wrapper_of(n)
    if os.path.exists(path):
        src = open(path).read()
        cpos = src.find('\nclass %s ' % n)
        dstart = src.rfind('/**', 0, cpos)
        dend = src.find('*/', dstart) + 2
        olddoc = src[dstart:dend]
        old_methods = [(mm.group(1), mm.group(0).strip(' *')) for mm in re.finditer(r'^\s*\*\s*@method\s+\S+\s+(\w+)\b.*$', olddoc, re.M)]
        wm = re.search(r"  protected \$wrapper = \[(.*?)\];", src, re.S)
        oldw = dict(re.findall(r"'(\w+)'\s*=>\s*(\w+)::class", wm.group(1))) if wm else {}
        merged = dict(w); merged.update(oldw)
        new = src[:dstart] + docblock(n, old_methods).rstrip('\n') + src[dend:]
        if wm:
            new = re.sub(r"  protected \$wrapper = \[(.*?)\];", lambda _: render_wrapper(merged), new, count=1, flags=re.S)
        else:
            new = new.replace('class %s extends Base\n{\n' % n, 'class %s extends Base\n{\n\n%s\n' % (n, render_wrapper(merged)), 1)
        if new != src:
            if not DRY: open(path, 'w').write(new)
            updated.append(n)
    else:
        if not DRY: open(path, 'w').write(HEADER + '\n' + docblock(n, []) + 'class %s extends Base\n{\n\n%s\n\n}\n' % (n, render_wrapper(w)))
        created.append(n)

print('Parser classes: %d created, %d updated' % (len(created), len(updated)))
if created: print('  created:', ' '.join(created))
if updated: print('  updated:', ' '.join(updated))

# ---- Bot API trait ----
bot_src = open(os.path.join(ROOT, 'src/Bot.php')).read()
existing = set(re.findall(r'public function (\w+)\s*\(', bot_src))

PTYPE = {'Integer': 'int', 'String': 'string', 'Boolean': 'bool', 'True': 'bool', 'Float': 'float', 'Float number': 'float'}
def ptype(t):
    if t in PTYPE: return PTYPE[t]
    if t == 'Integer or String': return 'int|string'
    if t == 'InputFile or String': return '\\CURLFile|string'
    if t == 'InputFile': return '\\CURLFile'
    return 'array'

out = []
uses = set()
for m in methods:
    n = m['name']
    if n in existing: continue
    req = [f for f in m['fields'] if len(f) >= 3 and f[2] == 'Yes']
    opt = [f for f in m['fields'] if len(f) >= 3 and f[2] != 'Yes']
    cls, arr, boolean, scalar = returns(m)
    if cls in union_members: cls = None
    if cls: uses.add(cls)
    if cls:
        rdoc = cls + ('[]' if arr else '') + ('|bool' if boolean else '|false')
    elif scalar:
        rdoc = scalar + '|false'
    else:
        rdoc = 'bool'
    doc = ['  /**']
    for p in m['desc']: doc.append('   * ' + clean(p))
    doc.append('   * @see https://core.telegram.org/bots/api#' + n.lower())
    doc.append('   *')
    for f in req:
        doc.append('   * @param %s $%s %s' % (ptype(f[1]), f[0], clean(f[3] if len(f) > 3 else '')))
    if opt:
        doc.append('   * @param array $%s Optional parameters:' % ('params' if any(f[0] == 'options' for f in req) else 'options'))
        for f in opt:
            doc.append('   *   - %s (%s) %s' % (f[0], f[1], clean(f[3] if len(f) > 3 else '')))
    doc.append('   * @return ' + rdoc)
    doc.append('   */')
    on = 'params' if any(f[0] == 'options' for f in req) else 'options'
    args = ['$' + f[0] for f in req] + (['array $%s = []' % on] if opt else [])
    data = "[" + ', '.join("'%s' => $%s" % (f[0], f[0]) for f in req) + "]"
    if opt: data = 'array_merge( %s, $%s )' % (data, on)
    extra = (", %s::class%s" % (cls, ', true' if arr else '')) if cls else ''
    body = "    return $this->apiCall( '%s', %s%s );" % (n, data, extra)
    sig = '( %s )' % ', '.join(args) if args else '()'
    out.append('\n'.join(doc) + '\n  public function %s%s\n  {\n%s\n  }\n' % (n, sig, body))

trait = """<?php
/**
 * Telegram Bot API methods for Joker
 *
 * Generated from https://core.telegram.org/bots/api %s
 * Required parameters are arguments, optional ones are passed in $options array.
 * Pass \\CURLFile as a value to upload a file (request becomes multipart/form-data).
 * Results are wrapped in Joker\\Parser classes, false is returned on error.
 * Methods with custom logic are defined in Bot class itself.
 *
 * @package joker-telegram-bot
%s
 */

namespace Joker;

%s

trait BotApi
{

  /**
   * Make Telegram Bot API request and wrap the result
   * @param string $method
   * @param array $data
   * @param string|null $class parser class to wrap the result in
   * @param bool $array result is an array of objects
   * @return mixed
   */
  protected function apiCall( string $method, array $data, $class = null, bool $array = false )
  {
    $multipart = false;
    foreach ($data as $value) if ($value instanceof \\CURLFile) { $multipart = true; break; }

    if ($multipart)
    {
      foreach ($data as $key => $value)
      {
        if ($value instanceof \\CURLFile) continue;
        if (is_bool($value)) $data[$key] = $value ? 'true' : 'false';
        elseif (is_array($value) || is_object($value)) $data[$key] = json_encode($value);
      }
      $result = $this->_requestMultipart( $method, $data );
    }
    else
    {
      $result = $this->_request( $method, $data );
    }

    if ($result === false || !$class || !is_array($result)) return $result;
    if ($array) return array_map(function ($item) use ($class) { return new $class($item, $this); }, $result);
    return new $class( $result, $this );
  }

%s
}
""" % ('(Bot API %s)' % VERSION if VERSION else '', AUTHOR, '\n'.join('use Joker\\Parser\\%s;' % u for u in sorted(uses)), '\n'.join(out))
if not DRY: open(os.path.join(ROOT, 'src/BotApi.php'), 'w').write(trait)
print('BotApi trait: %d methods, defined in Bot.php: %s' % (len(out), ', '.join(sorted(existing & {m['name'] for m in methods}))))
if DRY: print('Dry run, nothing written')
