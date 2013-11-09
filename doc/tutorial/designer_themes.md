[Up](../tutorial.md)

Themes
======
You can change No-CMS theme by accessing `Complete Menu | CMS Management | Change Theme` or `CMS Management | Change Theme`.

To set per-page theme, you can access `Complete Menu | Page Management` and set `Default Theme`

Themes are located at `/themes/` folder. With such a structure:
```
    /themes
        |--- [your_theme]
                |--- /assets
                |       |--- /default                       (consists of js, css, images etc)
                |       |--- /[other_layout]                (optional)
                |
                |--- /views
                        |--- /layouts
                        |       |--- default.php            (here is the main UI script)
                        |       |--- [other_layout].php     (optional)
                        |
                        |--- /partials
                                |--- /default
                                |--- /other_layout
```
In your `default.php`, you can several variables:

* `$template['body']` : This variable contains your page content.
* `$template['title']` : This variable contains your page title
* `$template['metadata']` : This variable contains everything including JQuery, meta keyword, and language information
* `$template['partials']['header']` : Including `/views/partials/default/header.php`.

You can also use several tags such as:
* `{{ site_logo }}` : Your logo path
* `{{ site_slogan }}` : Slogan
* `{{ site_footer }}` : Your footer
* `{{ widget_name:top_navigation }}` : A widget contains bootstrap styled top navigation
* `{{ widget_name:left_navigation }}` : A widget contains bootstrap styled top navigation
* And many others, see [designer tag's guide](designer_tags.md) for more information

Here is a very simple example of `default.php`:
```php
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title']; ?></title>
        <?php echo $template['metadata']; ?>
    </head>
    <body>
        <h1><img src=”{{ site_logo }}” /><?php echo $template['title']; ?></h1>
        <div class="nav-collapse in collapse" id="main-menu" style="height: auto; ">
            {{ widget_name:top_navigation }}
        </div>
        <?php echo $template['body']; ?>
    </body>
    <footer>{{ site_footer }}</footer>
</html>
```

Partials
========
For modularity sake, you might want to separate your layout into several part (e.g: header, footer, etc). Phil Sturgeon's template library (which is used in No-CMS) already support this. You can make as many partials as you need, and access it by using `$template['partials']['your_partial_name']` variable. Partial files should be located at /themes/your_theme_name/views/partials/default/. The previous example layout can be separated into several partials:

Content of `/themes/your_theme_name/views/layouts/default.php`:

```html
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo $template['title']; ?></title>
        <?php echo $template['metadata']; ?>
    </head>
    <body>
        <?php echo $template['partials']['header']; ?>
        <?php echo $template['body']; ?>
        <?php echo $template['partials']['footer']; ?>
    </body>    
    </html>
```

Content of `/themes/your_theme_name/views/partials/default/header.php`:

```html
    <h1><img src=”{{ site_logo }}” /><?php echo $template['title']; ?></h1>
    <div class="nav-collapse in collapse" id="main-menu" style="height: auto; ">
        {{ navigation_top_quicklink }}
    </div>
```

Content of `/themes/your_theme_name/views/partials/default/footer.php`:

```html
    <footer>{{ site_footer }}</footer>
```

__PS:__ The above example is a very minimalistic example to explain the concept. Please take a look at `/themes` folder to see the real `themes` example.
