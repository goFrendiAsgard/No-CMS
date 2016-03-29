[Up](../tutorial.md)

Themes
======
You can change No-CMS theme by accessing `Complete Menu | CMS Management | Change Theme` or `CMS Management | Change Theme`.

To set per-page theme, you can access `Complete Menu | Navigation Management` and set `Default Theme`

Themes are located at `/themes/` folder. With this structure:
```
    /themes
        |--- [your_theme]
                |--- /assets            (Contains your css, js, fonts, and other static files)
                |
                |--- /views
                |       |--- css.php    (how the css used in your theme)
                |       |
                |       |--- js.php     (how the js used in your theme)
                |
                |--- description.txt    (theme's description)
                |
                |--- preview.png

css.php
=======
Example of css.php:

```php
    <?php
    $asset = new Cms_asset();
    $asset->add_themes_css('css/bootstrap.min.css', '{{ used_theme }}');
    $asset->add_themes_css('css/style.css', '{{ used_theme }}');
    echo $asset->compile_css();
```

js.php
=======
Example of js.php:

```php
    <?php
    $asset = new Cms_asset();
    $asset->add_cms_js('bootstrap/js/bootstrap.min.js');
    $asset->add_themes_js('js/script.js', '{{ used_theme }}');
    echo $asset->compile_js();
```
