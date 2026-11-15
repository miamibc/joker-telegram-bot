<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a block in a rich formatted message. Currently, it can be any of the following types:
 * Currently can be one of RichBlockParagraph, RichBlockSectionHeading, RichBlockPreformatted, RichBlockFooter, RichBlockDivider, RichBlockMathematicalExpression, RichBlockAnchor, RichBlockList, RichBlockBlockQuotation, RichBlockExpandableBlockQuotation, RichBlockPullQuotation, RichBlockCollage, RichBlockSlideshow, RichBlockTable, RichBlockDetails, RichBlockMap, RichBlockButtons, RichBlockAnimation, RichBlockAudio, RichBlockDocument, RichBlockPhoto, RichBlockVideo, RichBlockVoiceNote, RichBlockThinking — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#richblock
 *
 * @method string type() Type of the block, always “paragraph”
 * @method RichText text() RichBlockParagraph, RichBlockSectionHeading, RichBlockPreformatted, RichBlockFooter, RichBlockExpandableBlockQuotation, RichBlockPullQuotation, RichBlockThinking only. Text of the block
 * @method integer size() RichBlockSectionHeading only. Relative size of the text font; 1-6, 1 is the largest, 6 is the smallest
 * @method string language() RichBlockPreformatted only. Optional. The programming language of the text
 * @method string expression() RichBlockMathematicalExpression only. The mathematical expression in LaTeX format
 * @method string name() RichBlockAnchor only. The name of the anchor
 * @method RichBlockListItem[] items() RichBlockList only. Items of the list
 * @method RichBlock[] blocks() RichBlockBlockQuotation, RichBlockCollage, RichBlockSlideshow, RichBlockDetails only. Content of the block
 * @method RichText credit() RichBlockBlockQuotation, RichBlockExpandableBlockQuotation, RichBlockPullQuotation only. Optional. Credit of the block
 * @method RichBlockCaption caption() RichBlockCollage, RichBlockSlideshow, RichBlockTable, RichBlockMap, RichBlockAnimation, RichBlockAudio, RichBlockDocument, RichBlockPhoto, RichBlockVideo, RichBlockVoiceNote only. Optional. Caption of the block
 * @method array cells() RichBlockTable only. Cells of the table
 * @method bool is_bordered() RichBlockTable only. Optional. True, if the table has borders
 * @method bool is_striped() RichBlockTable only. Optional. True, if the table is striped
 * @method bool is_compact() RichBlockTable only. Optional. True, if table cells have smaller indents
 * @method RichText summary() RichBlockDetails only. Always shown summary of the block
 * @method bool is_open() RichBlockDetails only. Optional. True, if the content of the block is visible by default
 * @method Location location() RichBlockMap only. Location of the center of the map
 * @method integer zoom() RichBlockMap only. Map zoom level
 * @method integer width() RichBlockMap only. Expected width of the map
 * @method integer height() RichBlockMap only. Expected height of the map
 * @method RichMessageButton[] buttons() RichBlockButtons only. The buttons
 * @method string align() RichBlockButtons only. Optional. Horizontal alignment of the buttons. Currently, must be one of “left”, “center”, or “right”.
 * @method Animation animation() RichBlockAnimation only. The animation
 * @method bool has_spoiler() RichBlockAnimation, RichBlockPhoto, RichBlockVideo only. Optional. True, if the media preview is covered by a spoiler animation
 * @method Audio audio() RichBlockAudio only. The audio
 * @method Document document() RichBlockDocument only. The document
 * @method PhotoSize[] photo() RichBlockPhoto only. Available sizes of the photo
 * @method Video video() RichBlockVideo only. The video
 * @method Voice voice_note() RichBlockVoiceNote only. The voice note
 */
class RichBlock extends Base
{

  protected $wrapper = [
    'text' => RichText::class,
    'items' => RichBlockListItem::class,
    'blocks' => RichBlock::class,
    'credit' => RichText::class,
    'caption' => RichBlockCaption::class,
    'summary' => RichText::class,
    'location' => Location::class,
    'buttons' => RichMessageButton::class,
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
    'photo' => PhotoSize::class,
    'video' => Video::class,
    'voice_note' => Voice::class,
  ];

}
