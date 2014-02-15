$(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('body')[0].offsetTop < ($(document).scrollTop()-$('.navbar-fixed-top').height())){
            $('.navbar-fixed-top').css({opacity: 0.95});
        }else{
            $('.navbar-fixed-top').css({opacity: 1});
        }
    });
    /*
    String.prototype.replace_insensitive = function(strReplace, strWith) {
        strReplace = strReplace.replace(/\//g, '\/');
        var reg = new RegExp(strReplace, 'ig');
        console.log(reg);
        console.log(strWith);
        console.log(this.replace(reg, strWith));
        return this.replace(reg, strWith);
    };
    
    function __replace_content(content, into_tag){
        var arr_1 = new Array('%7B%7B%20 site_url %20%7D%7D', '%7B%7B%20 base_url %20%7D%7D');
        var arr_2 = new Array('http://localhost/~gofrendi/No-CMS/', 'http://localhost/~gofrendi/No-CMS/');
        var pattern = new Array();
        var replace = new Array();
        if(into_tag){
            pattern = arr_2;
            replace = arr_1;
        }else{
            pattern = arr_1;
            replace = arr_2;
        }
        
        var $obj = $("<div>" + content + "</div>");
        $obj.find('img').each(function(){
            for(var i=0; i<pattern.length; i++){
                src = $(this).attr('src');
                src = src.replace_insensitive(pattern[i], replace[i]);
                $(this).attr('src', src);
                console.log($(this).attr('src'));
            }
        });
        $obj.find("a").attr("href", function (_, href) {
            for(var i=0; i<pattern.length; i++){
                href = href.replace_insensitive(pattern[i], replace[i]);
            }
            return href;
        }).find("link").attr("href", function (_, href) {
            for(var i=0; i<pattern.length; i++){
                href = href.replace_insensitive(pattern[i], replace[i]);
            }
            return href;
        }).find("img").attr("src", function (_, src) {
            for(var i=0; i<pattern.length; i++){
                src = src.replace_insensitive(pattern[i], replace[i]);
            }
            return src;
        }).find("script").attr("src", function (_, src) {
            for(var i=0; i<pattern.length; i++){
                src = src.replace_insensitive(pattern[i], replace[i]);
            }
            return src;
        });
        return $obj.html();
    }
    
    $('form').submit(function(){
        $('textarea').each(function () {
            var textarea = $(this);
            if(textarea.attr('name') in CKEDITOR.instances){
                textarea.val(CKEDITOR.instances[textarea.attr('name')].getData());
                textarea.val(__replace_content(textarea.val(), true));
            }
        });
    });
    
    $('.cke_button__source').live('click', function(){
        // button container
        var button_container = $(this).parent().parent().parent().parent().parent().parent()[0];
        for (instance in CKEDITOR.instances) {
            // ck_instance
            ck_instance = CKEDITOR.instances[instance];
            // CKEDITOR container
            ck_dom = ck_instance.container.$;
            // CKEDITOR container == button container
            if(ck_dom == button_container){
                var name = CKEDITOR.instances[instance].name;
                var textarea = $('#cke_'+name+' textarea');
                var iframe = $('#cke_'+name+' iframe');
                if(textarea.length > 0){
                    textarea.val(__replace_content(textarea.val(), true));
                    //console.log('textarea');
                    //console.log(textarea.val());
                }else{
                    // there is iframe
                    // console.log(iframe.contents().find("html").html());
                    //console.log( __replace_content(iframe.contents().find("html").html(), false));
                    
                        iframe.contents().find("html").html(
                            __replace_content(iframe.contents().find("html").html(), false)
                        );
                        
                    //console.log('iframe');
                    //console.log(iframe.contents().find("html").html());
                }
                ck_instance.updateElement();
                break;
            }
        }
    });
    */
});
