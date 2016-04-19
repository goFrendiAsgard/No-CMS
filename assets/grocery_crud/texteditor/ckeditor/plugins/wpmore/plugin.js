/**
 * CKEditor plugin for inserting simple More tag: <!--more-->
 *
 * This is modified from the WPMore plugin in the CKEditor extension for WordPress
 *
 * @link      [Demo] http://wordpress.ckeditor.com/
 * @link      [Source] http://wordpress.org/extend/plugins/ckeditor-for-wordpress/
 * @copyright Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
 * @license   For licensing, see LICENSE.html or http://ckeditor.com/license
 *
 * The docblock above is to acknowledge the original source and author.
 * The docblock below lists my information and the changelog. The @author tag
 * is just a phpDocumentor tag and does NOT imply claim of copyright or authorship of the original source.
 *
 * The most important change is the wrapping of text before and after the More tag.
 * Sample text: The quick brown fox jumps over the lazy old dog.
 * More tag inserted just before the word 'over'.
 * Original effect: <p>The quick brown fox jumps</p><!--more--><p>over the lazy old dog.</p>
 * Changed effect:  The quick brown fox jumps <!--more-->over the lazy old dog.
 *
 * Changelog:
 *   - Updated docblock
 *   - Removed docblock: "@file Plugin for inserting Drupal teaser and page breaks."
 *   - Updated comment <!--break--> to <!--more-->
 *   - Updated comment "cke_drupal_break" to "cke_wordpress_more"
 *   - Attempt to format code according to PSR-2
 *     @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
 *   - Moved text for confirm message into 'msg' variable
 *   - Changed (old): range.splitBlock('p');
 *             (new): range.splitBlock('span');
 *   - Commented out: if (!hasMoved) { range.splitBlock('span') };
 *   - Updated to work with both CKEditor v3 and v4
 *
 * @see    http://en.support.wordpress.com/splitting-content/more-tag/ on More tag
 * @author Zion Ng <zion@intzone.com>
 * @link   [Source] https://github.com/zionsg/CKEditor-More-plugin
 * @since  2012-11-24T10:00+08:00
 */

CKEDITOR.plugins.add('wpmore', {
    requires: ['fakeobjects'],

    getPlaceholderCss : function () {
        return 'img.cke_wordpress_more'
             + '{'
                 + 'background-image: url(' + CKEDITOR.getUrl(this.path + 'images/more_bug.gif') + ');'
                 + 'background-position: right center;'
                 + 'background-repeat: no-repeat;'
                 + 'clear: both;'
                 + 'display: block;'
                 + 'float: none;'
                 + 'width: 100%;'
                 + 'border-top: #999999 2px dotted;'
                 + 'height: 20px;'
             + '}'
    },

    onLoad : function () {
        // version 4 - add the styles that renders our fake objects
        if (CKEDITOR.addCss) {
            CKEDITOR.addCss(this.getPlaceholderCss());
        }
    },

    init: function (editor) {
        // version 3 - add the styles that renders our fake objects
        if (editor.addCss) {
            editor.addCss( this.getPlaceholderCss() );
        }

        // Register the toolbar buttons.
        editor.ui.addButton('WPMore', {
            label: 'Insert More Break',
            icon: this.path + 'images/more.gif',
            command: 'wpmore'
        });

        // Register the commands.
        editor.addCommand('wpmore', {
            exec: function () {
                // There should be only one <!--more--> tag in document. So, look
                // for an image with class "cke_wordpress_more" (the fake element).
                var images = editor.document.getElementsByTag('img');
                for (var i = 0, len = images.count() ; i < len ; i++ ) {
                    var img = images.getItem(i);
                    if (img.hasClass('cke_wordpress_more')) {
                        var msg = 'The document already contains a more. '
                                + 'Do you want to proceed by removing it first?'
                        if (confirm(msg)) {
                            img.remove();
                            break;
                        } else {
                            return;
                        }
                    }
                }

                insertComment('more');
            }
        });

        // This function effectively inserts the comment into the editor.
        function insertComment(text)
        {
            // Create the fake element that will be inserted into the document.
            // The trick is declaring it as an <hr>, so it will behave like a
            // block element (and in effect it behaves much like an <hr>).
            if (!CKEDITOR.dom.comment.prototype.getAttribute) {
                CKEDITOR.dom.comment.prototype.getAttribute = function () { return ''; };
                CKEDITOR.dom.comment.prototype.attributes = { align: '' };
            }
            var fakeElement = editor.createFakeElement(
                new CKEDITOR.dom.comment(text),
                'cke_wordpress_' + text,
                'hr'
            );

            // This is the trick part. We can't use editor.insertElement()
            // because we need to put the comment directly at <body> level.
            // We need to do range manipulation for that.

            // Get a DOM range from the current selection.
            var range = editor.getSelection().getRanges()[0],
                elementsPath = new CKEDITOR.dom.elementPath(range.getCommonAncestor(true)),
                element = (elementsPath.block && elementsPath.block.getParent()) || elementsPath.blockLimit,
                hasMoved;

            // If we're not in <body> go moving the position to after the
            // elements until reaching it. This may happen when inside tables,
            // lists, blockquotes, etc.
            while (element && element.getName() != 'body') {
                range.moveToPosition(element, CKEDITOR.POSITION_AFTER_END);
                hasMoved = 1;
                element = element.getParent();
            }

            // Commented out to prevent wrapping of blocks before and after More tag
            // Split the current block.
            // if (!hasMoved) {
                // range.splitBlock('span');
            // }

            // Insert the fake element into the document.
            range.insertNode(fakeElement);

            // Now, we move the selection to the best possible place following
            // our fake element.
            var next = fakeElement;
            while ((next = next.getNext()) && !range.moveToElementEditStart(next)) {
                // do nothing
            }

            range.select();
        } // end function insertComment

    }, // end function init

    afterInit: function (editor) {
        // Adds the comment processing rules to the data filter, so comments
        // are replaced by fake elements.
        editor.dataProcessor.dataFilter.addRules({
            comment: function (value) {
                if (!CKEDITOR.htmlParser.comment.prototype.getAttribute) {
                    CKEDITOR.htmlParser.comment.prototype.getAttribute = function() { return ''; };
                    CKEDITOR.htmlParser.comment.prototype.attributes = { align: '' };
                }
                if (value == 'more') {
                    return editor.createFakeParserElement(
                        new CKEDITOR.htmlParser.comment(value),
                        'cke_wordpress_' + value,
                        'hr'
                    );
                }
                return value;
            }
        });
    } // end function afterInit

}); // end add plugin 'wpmore'
