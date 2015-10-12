[Up](../tutorial.md)

Modules
=======

No-CMS use HMVC (Hierarchical Model-View-Controller) architecture.
Each module consists of MVC triad.

Let's start to make a new module:
```
    /modules
        |--- /new_module
                |--- /controllers
                |       |--- Pokemon.php
                |
                |--- /models
                |       |--- Pokemon_model.php
                |
                |--- /views
                        |--- pokemon_index.php
```

Make a controller
-----------------
Modify your `/modules/new_module/controllers/Pokemon.php` into this:
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
A controller should extend `CMS_Controller`, `CMS_Secure_Controller`, or `CI_Controller` and should has the same name as the file.
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

* In your view, you can also write some `tags`, e.g:

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

* Here is the list of those `tags`. Read the documentation for complete list:

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

Now edit your `/modules/new_module/models/Pokemon_model.php` into this:
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

Then edit your `/modules/new_module/controllers/Pokemon.php` into this:
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
    class Pokemon extends CMS_Secure_Controller{

        function show(){
            $this->load->model('new_module/pokemon_model');
            $data['pokemon_list'] = $this->pokemon_model->get();
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data);
        }

    }
```

By using `CMS_Secure_Controller` you do not need to define `navigation_code` when calling `$this->view()`.

__Note:__ If you want your url to have the same privilege as other url in the same controller, you can override
```php
   <?php
   class Your_Controller_Name extends CMS_Secure_Controller{
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
To make your module installable, you need to make 2 files. 
The first one is `description.txt`, and the second one is `info controller`. 

Let's make `description.txt` (`new_module/description.txt`)

```
{
    "dependencies"    : [],
    "name"            : "your_name.new_module",
    "description"     : "New Module based on tutorial to show pokemons",
    "version"         : "0.0.0"
}
``` 

Let's make your info controller (`new_module/controllers/Info.php`):
```php
    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Info extends CMS_Module {
        /////////////////////////////////////////////////////////////////////////////
        // Default Variables
        /////////////////////////////////////////////////////////////////////////////

        public $DEPENDENCIES = array();
        public $NAME         = 'your_name.new_module'; // namespace of your module
        public $DESCRIPTION  = 'New Module based on tutorial to show pokemons';
        public $VERSION      = '0.0.1';


        /////////////////////////////////////////////////////////////////////////////
        // Default Functions
        /////////////////////////////////////////////////////////////////////////////

        // ACTIVATION
        public function do_activate(){
            $this->remove_all();
            $this->build_all();
        }

        // DEACTIVATION
        public function do_deactivate(){
            $this->backup_database(array(
                'pokemons',
            ));
            $this->remove_all();
        }

        // UPGRADE
        public function do_upgrade($old_version){
            // Add your migration logic here.
        }

        /////////////////////////////////////////////////////////////////////////////
        // Private Functions
        /////////////////////////////////////////////////////////////////////////////

        // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function remove_all(){

            // remove navigations
            $this->cms_remove_navigation('pokemon_list');

            // drop table
            $this->dbforge->drop_table('pokemons', TRUE);
            // If you prefer to work with raw SQL, this one will also works:
            // $this->db->query('DROP TABLE IF EXISTS `pokemons`');
        }

        // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function build_all(){
            $module_path = $this->cms_module_path();

            // parent of all navigations
            $this->cms_add_navigation('pokemon_list', 'Pokemon List',
                $module_path.'/pokemon/show', PRIV_EVERYONE);

            // create table
            $fields = array(
                'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
                'name'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE),
                'description' => array("type"=>'text'),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('pokemons');
            // If you prefer to work with raw SQL, this one will also works:
            // $this->db->query('CREATE TABLE `pokemons` (
            //      `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
            //      `name` varchar(45) DEFAULT NULL,
            //      `description` text,
            //      PRIMARY KEY (`id`)
            //    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
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
            $file_name = 'backup_'.date('Y-m-d_G-i-s').'.sql';
            file_put_contents(
                    BASEPATH.'../modules/'.$module_path.'/assets/db/'.$file_name,
                    $sql
                );

        }
    }
```

There are several property and method you must provide:

Property:

* public $DEPENDENCIES
    
    An array, contains dependency of your module.

* public $NAME
    
    Namespace of your module (has nothing to do with PHP namespace).
    Every module should have different namespace

* public $DESCRIPTION

    Description of your module

* public $VERSION

    Your module version


Method:

* public function do_activate()

    Your module activation logic (add navigation, widget, etc)

* public function do_deactivate()

    Your module deactivation logic (remove navigation, widget, etc)

* public function do_upgrade($old_version)

    Your module upgrade logic (will be executed automatically once you change $VERSION)

Now, you can go to `CMS Management | Module Management` and activate/deactivate new_module
