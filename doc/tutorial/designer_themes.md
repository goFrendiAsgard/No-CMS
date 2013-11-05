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
* And many others, see documentation for more information

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