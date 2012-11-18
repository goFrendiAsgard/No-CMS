$(function(){
	$( 'textarea.texteditor' ).ckeditor({
        baseHref : "{{ base_path }}", 
        baseUrl  : "{{ base_path }}",
    });
});