[Up](../tutorial.md)

Widgets
=======

Widgets are part of the website that always appear in all pages.
No-CMS has several built-in widgets.

Let's add new widget
* Open `Complete Menu | CMS Management | Widget Management` or `CMS Management | Widget Management`. There is already several built-in widgets
* Add new widget by clicking `Add Widget`
* Set `Widget Code` into `my_widget`
* Set `Static` into `active`
* Set `Static Content` into

```html
    <strong>Hello there</strong> 
    <p>you can put html tags, and even a black cat here:</p>
    <embed src="http://s3.amazonaws.com/wbx-files/maukie.swf" width="330" height="400" type="application/x-shockwave-flash" id="widgetbox_widget_flash_0" name="widgetbox_widget_flash_0" allowscriptaccess="sameDomain" bgcolor="FFFFFF" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer">
```
* Set Slug into `sidebar`
* Click `Save and Go Back to List`

Now look at the right side of your site. There should be a black cat appeared (if you have flash player).

You can put this widget everywhere.
You can put a single widget by using `{{ widget_name:your_widget_name }}`.
You can also put a group of widgets by using `{{ widget_slug:your_widget_slug }}`.
You can even put widget as part of your page by editing your page static content.