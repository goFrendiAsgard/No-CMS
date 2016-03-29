Layouts
=======

You can manage your layouts by accessing `CMS Management | Layout Management`. Layout define how your pages looked. To modify a layout you need to know some basic HTML.

There are some special tags you can use in layouts:
* `{{ layout:title }}` This tag will be rendered and replaced by page title
* `{{ layout:metadata }}` This tag will be rendered and replaced by default metadata
* `{{ layout:js }}` This tag will be rendered and replaced by No-CMS and theme's predefined javascript (See `js.php` in your `themes/your-theme/views`)
* `{{ layout:css }}` This tag will be rendered and replaced by No-CMS and theme's predefined css (See `css.php` in your `themes/your-theme/views`)
* `{{ layout:body }}` This tag will be rendered and replaced by current page's content. You should ensure your layout contains this tag.

Example
=======

Here is the default layout provided by No-CMS:

```html
<!DOCTYPE html>
<html lang="{{ language:language_alias }}">
    <head>
        <meta charset="utf-8">
        <title>{{ layout:title }}</title>
        {{ layout:metadata }}
        <link rel="icon" href="{{ site_favicon }}">
        <!-- Le styles -->
        {{ layout:css }}
        <style type="text/css">{{ widget_name:section_custom_style }}</style>
        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="{{ site_favicon }}">        
    </head>
    <body>
        {{ layout:js }}
        <script type="text/javascript">{{ widget_name:section_custom_script }}</script>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="{{ BASE_URL }}assets/no_cms/js/html5.js"></script>
            <script src="{{ BASE_URL }}assets/no_cms/js/respond.min.js"></script>
        <![endif]-->

        {{ widget_name:section_top_fix }}
        <div class="container">
            <div class="row-fluid">
                <div id="__section-banner">{{ widget_name:section_banner }}</div>
                <div>
                    <div id="__section-left-and-content" class="col-md-9">
                        <div>{{ navigation_path }}</div><hr />
                        <div>
                            <div id="__section-left" class="hidden">
                                {{ widget_name:section_left }}                                    
                            </div>
                            <div id="__section-content" class="col-md-12">
                                {{ layout:body }}
                            </div>
                        </div>
                    </div><!--/#layout-content-->
                    <div id="__section-right" class="col-md-3">
                        {{ widget_name:section_right }}
                    </div><!--/#layout-widget-->
                </div>
            </div><!--/row-->
          <hr>
        </div><!--/.fluid-container-->
        <footer>{{ widget_name:section_bottom }}</footer>
        <script type="text/javascript">
            $(document).ready(function(){
                // if section-left is empty, remove it
                if($.trim($('#__section-left').html()) == ''){
                    $('#__section-left').remove();
                }else{
                    $('#__section-content').removeClass('col-md-12');
                    $('#__section-content').addClass('col-md-9');
                    $('#__section-left').removeClass('hidden');
                    $('#__section-left').addClass('col-md-3');
                }
                // if section-right is empty, remove it
                if($.trim($('#__section-right').html()) == ''){
                    $('#__section-right').remove();
                    $('#__section-left-and-content').removeClass('col-md-9');
                    $('#__section-left-and-content').addClass('col-md-12');
                }
                // if section-banner is empty, remove it
                if($.trim($('__section-banner').html()) == ''){
                    $('__section-banner').remove();
                }
            });
        </script>
    </body>
</html>
```
