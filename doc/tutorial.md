Tutorial 00: Installation
=========================

* Download No-CMS from [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS) on GitHub
* Copy and extract it on your web server directory (You might want to try it locally via xampp, in this case, your server directory is c:\xampp\htdocs)
* Access the url (If you use xampp in your local computer, the url should be http://localhost/No-CMS)
* Click "Install Now"
* Fill any information needed (including your administrator password)


Tutorial 01: Navigations & Pages
================================

A website is actually collection of web-pages. Making a page is a very crucial feature in every common-used CMS.

No-CMS allow you to make your own page:

* Login to No-CMS with your admin user.
* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `new_page`
* Set `Navigation Title` into `Go to My Page`
* Set `Page Title` into `My First Page`
* Set `Static` into `active`
* Set `Static Content` into `Hello World !!!`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

You can access your new page by clicking `Complete Menu | Go to My Page`.
This new page can be accessed by `Everyone`. No-CMS has several authorization type:
* __Everyone__: Can be accessed by all visitor.
* __Unauthenticated__: Can only be accessed by not currently login visitor.
* __Authenticated__: Can only be accessed by already login visitor
* __Authorized__: Can only be accessed by already login visitor who is also member of certain `group`

Let's add another page as sub-page:

* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `new_subpage`
* Set `Parent` into `new_page` (Navigation Code of our previous page)
* Set `Navigation Title` into `Go to My Subpage`
* Set `Page Title` into `My Second Page`
* Set `Static` into `active`
* Set `Static Content` into `Great, you made it !!!`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

You can access this page by clicking `Complete Menu | Go to My Page | Go to My Subpage`

As you application grow, some frequently accessed page might be burried down in the navigation hierarchy.
No-CMS has `quick link` to solve such a problem.

Now let's make a quick link:

* Open `Complete Menu | CMS Management | Quick Link Management` or `CMS Management | Quick Link Management`
* Add new quick link by clicking `Add Quick Link`
* Set `Navigation Code` into `new_subpage`
* Click `Save and Go Back to List`

Now, you can access `new_subpage` directly.


Tutorial 02: Widgets
====================

Widgets are part of the website that always appear in all pages.
No-CMS has several built-in widgets.

Let's add new widget
* Open `Complete Menu | CMS Management | Widget Management` or `CMS Management | Widget Management`. There is already several built-in widgets
* Add new widget by clicking `Add Widget`
* Set `Widget Code` into `fb_badge`
* Set `Static` into `active`
* Set `Static Content` into

```html
    <!-- Facebook Badge START -->
    <a href="https://www.facebook.com/goFrendiAsgard" target="_TOP"
        style="font-family: &quot;lucida grande&quot;,tahoma,verdana,arial,sans-serif; font-size: 11px; font-variant: normal; font-style: normal; font-weight: normal; color: #3B5998; text-decoration: none;"
        title="Go Frendi Asgard">Go Frendi Asgard
    </a><br/>
    <a href="https://www.facebook.com/goFrendiAsgard" target="_TOP"
        title="Go Frendi Asgard">
        <img src="https://badge.facebook.com/badge/696121596.3142.1524306206.png" style="border: 0px;" />
    </a><br/>
    <a href="https://www.facebook.com/badges/" target="_TOP"
        style="font-family: &quot;lucida grande&quot;,tahoma,verdana,arial,sans-serif; font-size: 11px; font-variant: normal; font-style: normal; font-weight: normal; color: #3B5998; text-decoration: none;"
        title="Make your own badge!">Create Your Badge
    </a>
    <!-- Facebook Badge END -->
```
* Set Slug into `sidebar`
* Click `Save and Go Back to List`

Now look at the right side of your site. There should be a my facebook badge.

You can put this widget everywhere.
For example, open up `/themes/neutral/views/layouts/default.php` and put `{{ widget_name:fb_badge }}`
The changes in themes will be applied globally in all of your page.

You can put a single widget by using `{{ widget_name:your_widget_name }}`.
You can also put a group of widgets by using `{{ widget_slug:your_widget_slug }}`.
You can even put widget as part of your page by editing your page static content.

Tutorial 03: Themes
===================

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

Tutorial 04: Modules
====================

No-CMS use HMVC (Hierarchical Model-View-Controller) architecture.
Each module consists of MVC triad.

Let's start to make a new module:
```
    /modules
        |--- /new_module
                |--- /controllers
                |       |--- pokemon.php
                |       |--- install.php
                |
                |--- /models
                |       |--- pokemon_model.php
                |
                |--- /views
                        |--- pokemon_index.php
```

Make a controller
-----------------
Modify your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            echo '<ul>
                    <li>pikachu</li>
                    <li>charmender</li>
                    <li>bulbasur</li>
                    <li>squirtle</li>
                </ul>';
        }

    }
```
Now, you have a controller in `/modules/new_module/controllers/pokemon.php`.
A controller should extend `CMS_Controller`, `CMS_Priv_Strict_Controller`, or `CI_Controller` and should has the same name as the file.
Since your file name is `pokemon.php`, your controller class name should be `Pokemon`.
In the `Pokemon` Controller, you have a function called `show` that return a bunch of html.

Now open up your browser and access this page: `http://localhost/No-CMS_directory/new_module/pokemon/show`.
You will see a page contains some pokemons.

Notice that since No-CMS is based on CodeIgniter, the url doesn't imply directory structure.
Basically, this is the general rule:
````
    http://server:port/No-CMS_directory/module_name/controller_name/function_name/parameter_1/parameter_2
````
Please take a look at CodeIgniter & HMVC user guide and tutorials if you are not familiar with this.

Make a view
-----------
It is better to separate your presentation from your controller. Therefore we should have a view.

Now edit your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            $pokemon_list = array('pikachu',
                'bulbasur', 'charmender',
                'squirtle', 'caterpie',
                'articuno', 'ekans', 'koffing'
            );
            $data['pokemon_list'] = $pokemon_list;
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data, 'main_index');
        }

    }
```

And modify your `/modules/new_module/views/pokemon_index.php` into this:
```php
    <?php echo "Welcome, ".$name; ?>
    <ul>
        <?php
        foreach($pokemon_list as $pokemon){
            echo '<li>'.$pokemon.'</li>';
        }
        ?>
    </ul>
```

The controller is now handling `$data` to `new_module/views/pokemon_index` and show the page with `main_index` privilege.
In `pokemon_index.php`, every key on `$data` are become new variables (in this case `$data['name']` become `$name` and `$data['pokemon_list']` become `$pokemon_list`).

A bit more about view
---------------------
* To load a view by using controller, and parse some data on it, you can write:

```php
    $this->view('view_name', $data);
```

* To load a view by using controller, and make sure that only users with certain navigation can see it, you can write:

```php
    $this->view('view_name', $data, 'navigation_code_required');
```

* To load a view by using controller, and make sure that only users with certain navigation & privileges can see it, and use custom title and keyword, you can write:

```php
    $config = array(
        'privileges' => array('priv_1', 'priv_2'),
        'title' => 'page_title',
        'keyword' => 'home page, No-CMS, cool',
    );
    $this->view('view_name', $data, 'navigation_code_required', $config);
```

* If you want to have the result returned as variable instead of written to output buffer, you can add 5th parameter:

```php
    $config = array(
        'privileges' => array('priv_1', 'priv_2'),
        'title' => 'page_title',
        'keyword' => 'home page, No-CMS, cool',
    );
    $result = $this->view('view_name', $data, 'navigation_code_required', $config, TRUE);
```

* In your view, you can also write some "magical" keywords, e.g:

```html
    <h3>{{ language:Welcome }} {{ user_name }}</h3>
    <p>Your current language setting is {{ language }}</p>
    {{ if_language:english }}
        <p>Nice to meet you, {{ user_real_name }}</p>
    {{ elif_language:japanese }}
        <p>始めまして, {{ user_real_name }}　さん</p>
    {{ elif_language:indonesian }}
        <p>Senang bertemu dengan anda, {{ user_real_name }}</p>
    {{ end_if }}
    <script type="text/javascript" src="{{ base_url }}/assets/nocms/js/jquery.js"></script>
```

* Here is the list of those magical keywords (or also known as tag). Read the documentation for complete list:

```
    {{ user_id }}
    {{ user_name }}
    {{ user_real_name }}
    {{ user_email }}
    {{ site_url }}
    {{ base_url }}
    {{ module_path }}
    {{ widget_slug:slug }}
    {{ widget_name:widget_name }}
    {{ language }}
    {{ language:some_word }}

    {{ if_language:a_language }}
       something that will be appeared for a language
    {{ elif_language:another_language }}
        something that will be appeared for another language
    {{ else }}
        something that will be appeared for another case
    {{ end_if }}
```


Make a model
------------

Right now, we have already learn about controller and view. How about model?
Model is the heart of your application. It should define what your module can do. In CodeIgniter, this is usually violated. Some user put logic on controller. It is okay, but not the best practice.
Let's say you have a pokemon table in No-CMS database:
```sql
    CREATE TABLE `pokemons` (
      `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(45) DEFAULT NULL,
      `description` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT INTO `pokemons`(`name`) VALUES
        ('pikachu'),('bulbasur'),('squirtle'),('charmender'),('caterpie'),
        ('ekans'),('koffing'),('magnemite'),('articuno'),('ivy');
```
You want to show list of pokemons based on the table content.

Now edit your `/modules/new_module/models/pokemon_model.php` into this:
```php
    <?php
    class Pokemon_Model extends CMS_Model{

        function get(){
            $query = $this->db->get('pokemons');
            // or you can use this too:
            //  $query = $this->db->query('SELECT * FROM pokemons');
            $pokemon_list = array();
            foreach($query->result() as $row){
                $pokemon_list[] = $row->name;
            }
            return $pokemon_list;
        }

    }
```

Then edit your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            $this->load->model('new_module/pokemon_model');
            $data['pokemon_list'] = $this->pokemon_model->get();
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data, 'main_index');
        }

    }
```

You can use the same model in many controllers. This will keep your application DRY (Don't repeat yourself).

__NOTE:__ Actually I do not like how CodeIgniter map `$this->load->model('new_module/pokemon_model')` into `$this->pokemon_model`.
This is both make IDE autocompletion doesn't work and make application less implicit. However you can use this code to keep autocompletion work with very small performance drawback:
```php
    $this->load->model('new_module/pokemon_model');
    $this->pokemon_model = new Pokemon_Model();
```
This way, you will have autocompletion and full controll of instance name.


Register the page and better authorization
------------------------------------------
So far we can access the page with directly access the url `http://localhost/No-CMS_directory/new_module/pokemon/show`.
Now, let's integrate it with No-CMS so that use can access it by clicking `Complete Menu | Pokemon List`.

Just simply make a page as in our previous tutorial:
* Login to No-CMS with your admin user.
* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `pokemon_list`
* Set `Navigation Title` into `Pokemon List`
* Set `Page Title` into `Get these Pokemons`
* Set `Static` into `inactive`
* Set `url` into `new_module/pokemon/show`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

After register the page, now change your controller a bit:

```php
    <?php
    class Pokemon extends CMS_Priv_Strict_Controller{

        function show(){
            $this->load->model('new_module/pokemon_model');
            $data['pokemon_list'] = $this->pokemon_model->get();
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data);
        }

    }
```

By using `CMS_Priv_Strict_Controller` you do not need to define `navigation_code` when calling `$this->view()`.

__Note:__ If you want your url to have the same privilege as other url in the same controller, you can override
```php
   <?php
   class Your_Controller_Name extends CMS_Priv_Strict_Controller{
        /*
         * URL_MAP will be used in case of you have "unregistered function"
         * (e.g : you don't have any navigation name that refer to
         * 'your_module_name/your_controller_name/unregistered_function',
         * but you want to make sure that the url has the same authorization as 'a_navigation_name')
         */
        protected function do_override_url_map($URL_MAP){
           $URL_MAP['your_module_name/your_controller_name/unregistered_function'] = 'a_navigation_name';
           return $URL_MAP;
        }

        /*
         * This is the normal way. You can access below function by using this url:
         * http://your_domain.com/No-CMS_installation_folder/your_module_name/your_controller_name/show
         */
        public function show(){
            $this->load->model('your_model_name');
            $data = array();
            $data['result'] = $this->your_model_name->get_data();
            $this->view('your_view_name', $data);
        }

        /*
         * This is gonna be work to, even if the url is not registered.
         * You can access below function by using this url:
         * http://your_domain.com/No-CMS_installation_folder/your_module_name/your_controller_name/strict
         */
        public function unregistered_function(){
            $this->load->model('your_model_name'); // this will not be run if visitor not authorized
            $data = array();
            $data['result'] = $this->your_model_name->get_data();
            $this->view('your_view_name', $data);
        }
   }
   ?>
```


Make module installable
-----------------------
Make installable module is basically can be done by making automation script of page registration.
Modify your `new_module/controllers/install.php` into this:
```php
    <?php
    class Install extends CMS_Module_Installer {
        /////////////////////////////////////////////////////////////////////////////
        // Default Variables
        /////////////////////////////////////////////////////////////////////////////

        protected $DEPENDENCIES = array();
        protected $NAME         = 'your_name.new_module'; // namespace of your module
        protected $DESCRIPTION  = 'New Module based on tutorial to show pokemons';
        protected $VERSION      = '0.0.1';


        /////////////////////////////////////////////////////////////////////////////
        // Default Functions
        /////////////////////////////////////////////////////////////////////////////

        // ACTIVATION
        protected function do_activate(){
            $this->remove_all();
            $this->build_all();
        }

        // DEACTIVATION
        protected function do_deactivate(){
            $this->backup_database(array(
                'pokemons',
            ));
            $this->remove_all();
        }

        // UPGRADE
        protected function do_upgrade($old_version){
            // Add your migration logic here.
        }

        /////////////////////////////////////////////////////////////////////////////
        // Private Functions
        /////////////////////////////////////////////////////////////////////////////

        // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function remove_all(){
            // remove navigations
            $this->remove_navigation('pokemon_list');
            // import uninstall.sql
            $this->db->query('DROP TABLE IF EXISTS `pokemons`');
        }

        // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function build_all(){
            $module_path = $this->cms_module_path();

            // parent of all navigations
            $this->add_navigation('pokemon_list', 'Pokemon List',
                $module_path.'/pokemon/show', $this->PRIV_EVERYONE);


            $this->db->query('CREATE TABLE `pokemons` (
                  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(45) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }

        // EXPORT DATABASE
        private function backup_database($table_names, $limit = 100){
            $module_path = $this->cms_module_path();

            $this->load->dbutil();
            $sql = '';

            // create DROP TABLE syntax
            for($i=count($table_names)-1; $i>=0; $i--){
                $table_name = $table_names[$i];
                $sql .= 'DROP TABLE IF EXISTS `'.$table_name.'`; '.PHP_EOL;
            }
            if($sql !='')$sql.= PHP_EOL;

            // create CREATE TABLE and INSERT syntax
            $prefs = array(
                    'tables'      => $table_names,
                    'ignore'      => array(),
                    'format'      => 'txt',
                    'filename'    => 'mybackup.sql',
                    'add_drop'    => FALSE,
                    'add_insert'  => TRUE,
                    'newline'     => PHP_EOL
                  );
            $sql.= $this->dbutil->backup($prefs);

            //write file
            $file_name = 'backup_'.date('Y-m-d_G:i:s').'.sql';
            file_put_contents(
                    BASEPATH.'../modules/'.$module_path.'/assets/db/'.$file_name,
                    $sql
                );

        }
    }
```

Now, you can go to `CMS Management | Module Management` and activate/deactivate new_module


Tutorial 05: Module Generator (Nordrassil)
==========================================

* Go to `CMS Management | Module Generator`
* Make a new project. (You can make a project based on already exists database)
* Edit or make tables and columns for your project
* Click generate, and it is.
* Go to `CMS Management | Module Management`, activate your module

Tutorial 06: Migration
======================

Update 
------
If you have git installed, updating No-CMS to the newest version is going to be easy. As easy as you type:
```git fetch origin master```

For non git user, you can just download the newest and safely overwrite everything except:
* `/application/config/` directory
* `/.htaccess` file

From local computer to server
-----------------------------

In case of you test No-CMS in local computer and want to upload it into public server, you need to change these parts:
* `RewriteBase` in `/.htaccess`
* Database configuration in `/application/config/database.php`

RewriteBase should be `/` if you put No-CMS in top public directory (eg: * If your web address is http://some_domain.com, then you should edit RewriteBase into `RewriteBase /`).

RewriteBase should be `/your_folder` if you put No-CMS inside a directory in your public directory (eg: * If your web address is http://some_domain.com/portal/, then you should edit RewriteBase into `RewriteBase /portal/`)

Tutorial 07: Translation
========================

To make additional language translation, you can copy `/assets/nocms/languages/english.php` to `/assets/nocms/languages/your_language.php`.

Beside that, No-CMS also support per-module translation. Go to `modules/assets/languages/` directory and make your translation file.