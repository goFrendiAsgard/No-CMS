$(document).ready(function(){
    // change texarea into ace code editor
    $("#template").ace({
        theme: "eclipse",
        lang: "html",
        width: "100%",
        height: "400px"
    });
    $("#template").each(function(){
        var decorator = $(this).data("ace");
        var aceInstance = decorator.editor.ace;
        aceInstance.setFontSize("16px");
    });
    // even if restore clicked
    $('#btn-restore').click(function(event){
        event.preventDefault();
        $("#template").each(function(){
            var decorator = $(this).data("ace");
            var aceInstance = decorator.editor.ace;
            aceInstance.setValue($('#default_template').val());
        });
    });
});
