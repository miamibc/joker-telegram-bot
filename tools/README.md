# Tools

Developer tools for Joker Telegram Bot. They are not used by the bot at runtime,
run them by hand from the project root when needed.

Every tool has a short description at the top of the file and `--help` option.
When adding a new tool, put it in this folder and describe it below: what it does,
what it needs, how to run it.

## Available tools

* [generate-bot-api.py](#generate-bot-apipy) - generate Bot API methods and parser classes from Telegram documentation

### generate-bot-api.py

Reads [Telegram Bot API documentation](https://core.telegram.org/bots/api) and generates:

- `src/BotApi.php` - trait with all Bot API methods, used by `Joker\Bot`. Methods defined in `src/Bot.php`
  by hand are skipped. Required parameters become arguments, optional ones go to the last `$options` array.
  Parameters marked "required unless ..." (`chat_id` of `send*` methods, optional only for ephemeral replies)
  are arguments too, `null` skips them. Results are wrapped in `Joker\Parser` classes. Passing `\CURLFile` as a value uploads a file (multipart request).
- `src/Parser/*.php` - parser classes for all types that can come from Telegram (reachable from `Update`
  and method results). Union types (`ChatMember`, `ReactionType`, `MessageOrigin`...) are parsed by one class
  with fields of all subtypes. Existing classes keep their custom code: only docblock and `$wrapper` list are
  updated, custom wrappers (like `Text` for `text` and `caption`) and fields removed from documentation are kept.
  Types used only for sending (`InputMedia`, `InlineQueryResult`...) get no classes, pass them as arrays.

Running it again on the same documentation changes nothing, so after a new Bot API release
just run it, review `git diff` and run tests: `tests/Bot/IntegrityTest.php` checks that all classes
used by parsers and methods exist.

Requires Python 3, standard library only.

```bash
python3 tools/generate-bot-api.py                  # download documentation and generate
python3 tools/generate-bot-api.py --dry-run        # show what would be changed, write nothing
python3 tools/generate-bot-api.py --html api.html  # use saved documentation page
python3 tools/generate-bot-api.py --json api.json  # also save parsed documentation as JSON
```

Documentation is parsed from HTML, so if Telegram changes the page layout significantly,
the parser (`parse()` function) may need an update.
