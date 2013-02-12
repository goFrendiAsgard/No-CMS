$(function(){
	$( 'textarea.texteditor' ).ckeditor({toolbar:'Full'});
	$( 'textarea.mini-texteditor' ).ckeditor({toolbar:'Basic',width:700});
	$( 'textarea.texteditor' ).ckeditor({
        baseHref : "{{ base_path }}", 
        baseUrl  : "{{ base_path }}",
    });
});