/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
var BASE_URL = '';
if(typeof(__cms_base_url) == 'undefined'){
    BASE_URL = '{{ BASE_URL }}';
}else{
    BASE_URL = __cms_base_url;
}


CKEDITOR.editorConfig = function( config )
{
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.scayt_autoStartup = false;
    config.allowedContent = 'img form input param pre flash br a td p span font em strong table tr th td style  script iframe u s li ul div[*]{*}(*)';// add every html element you'll use in your eml tempate,I don't test if '*[*]{*}(*) will work for all html tag?
    
    config.extraPlugins = 'wpmore'; // Add 'WPMore' plugin - must be in plugins folder
    config.toolbar = [
        ['Source', 'WPMore'] // Add 'WPMore' button to toolbar
    ];

    // Use <br> as break and not enclose text in <p> when pressing <Enter> or <Shift+Enter>
    config.enterMode = CKEDITOR.ENTER_P;
    config.shiftEnterMode = CKEDITOR.ENTER_BR;
    config.fillEmptyBlocks = false;    // Prevent filler nodes in all empty blocks

    // Remove all formatting when pasting text copied from websites or Microsoft Word
    //config.forcePasteAsPlainText = true;
    //config.pasteFromWordRemoveFontStyles = true;
    //config.pasteFromWordRemoveStyles = true;

    //config.enterMode = CKEDITOR.ENTER_P;
    //config.shiftEnterMode = CKEDITOR.ENTER_BR;
    config.forcePasteAsPlainText = false; // default so content won't be manipulated on load
    //config.basicEntities = true;
    //config.entities = true;
    //config.entities_latin = false;
    //config.entities_greek = false;
    //config.entities_processNumerical = false;
    config.allowedContent = true; // don't filter my data

    config.filebrowserBrowseUrl = BASE_URL+'assets/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = BASE_URL+'assets/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = BASE_URL+'assets/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = BASE_URL+'assets/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = BASE_URL+'assets/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = BASE_URL+'assets/kcfinder/upload.php?type=flash';
};


CKEDITOR.config.fillEmptyBlocks = function (element) {
        return true; // DON'T DO ANYTHING!!!!!
};

CKEDITOR.on( 'instanceReady', function( ev )
{
    var writer = ev.editor.dataProcessor.writer;
    // The character sequence to use for every indentation step.
    writer.indentationChars = '    ';

    var dtd = CKEDITOR.dtd;

    if(typeof(ev.editor.dataProcessor.writer.setRules) != 'undefined'){

        // Elements taken as an example are: block-level elements (div or p), list items (li, dd), and table elements (td, tbody).
        for ( var e in CKEDITOR.tools.extend( {}, dtd.$block, dtd.$listItem, dtd.$tableContent, dtd.$nonEditable ) )
        {
            ev.editor.dataProcessor.writer.setRules( e, {
                // Indicates that an element creates indentation on line breaks that it contains.
                indent : true,
                // Inserts a line break before a tag.
                breakBeforeOpen : true,
                // Inserts a line break after a tag.
                breakAfterOpen : true,
                // Inserts a line break before the closing tag.
                breakBeforeClose : true,
                // Inserts a line break after the closing tag.
                breakAfterClose : false
            });
        }

        for ( var e in CKEDITOR.tools.extend( {}, dtd.$list, dtd.$listItem, dtd.$tableContent ) )
        {
            ev.editor.dataProcessor.writer.setRules( e, {
                indent : true,
            });
        }


        var indented_element = new Array('table', 'form');
        for(var e in indented_element){
            ev.editor.dataProcessor.writer.setRules( e,
            {
                indent : true,
            });
        }

        var single_tag = new Array('br', 'source');
        for(var e in single_tag){
            ev.editor.dataProcessor.writer.setRules( e,
            {
                breakAfterOpen : true,
            });
        }
        // You can also apply the rules to a single element.
        /*
        ev.editor.dataProcessor.writer.setRules( 'table',
        {
            indent : true,
        });

        ev.editor.dataProcessor.writer.setRules( 'form',
        {
            indent : true,
        });
        ev.editor.dataProcessor.writer.setRules( 'source',
        {
            breakAfterOpen : true,
        });
        */
    }

});
