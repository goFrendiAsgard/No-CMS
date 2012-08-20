CREATE TABLE `help_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `url` varchar(60) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `help_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `url` varchar(60) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `url` (`url`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `help_topic_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `help_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*split*/
INSERT INTO `help_group`(`id`,`name`,`url`,`content`) VALUES
  ('1', 'Basic Info', 'basic-info', 'This section contains basic info of No-CMS'),
  ('2', 'Installation', 'installation', 'This section contains installation of No-CMS'),
  ('3', 'Getting Started', 'getting-started', 'Fasten your seatbelt and get started'),
  ('4', 'More With Module', 'more-with-module', 'More advance topics about modules'),
  ('5', 'More With Theme', 'more-with-theme', 'More advance topics about theme'),
  ('6', 'Module Generator', 'module-generator', 'Make your life easier with Module Generator'),
  ('7', 'No-CMS Advance Topic', 'no-cms-advance-topic', '<p>
  No-CMS advance topics</p>
'),
  ('8', 'FAQ', 'faq', '<p>
   Frequently Asked Questions</p>
');

/*split*/
INSERT INTO `help_topic`(`id`,`group_id`,`title`,`url`,`content`) VALUES
  ('1', '1', 'Overview', 'overview', '<p>
   No-CMS is a CMS-framework. It is a <a href=\"help/topic/no-cms_as_cms\">CMS</a> and a <a href=\"help/topic/no-cms_as_framework\">framework</a> in the same time. No-CMS is a basic CMS with some default features such as user authorization, menu, module and theme management. It is fully customizable and extensible, you can make your own module and your own themes. It provide freedom to make your very own CMS, which is not provided very well by any other CMS.</p>
'),
  ('2', '1', 'Who is No-CMS for', 'who-is-no-cms-for', 'No CMS will be good for you if you say yes for majority of these
statement:
<ul>
   <li>You are a web developer who use CodeIgniter framework.</li>
   <li>You are tired of building the same things such an
     authorization-authentication for every project.</li>
  <li>You find that some part of your old project can be used for your
      next project.</li>
    <li>You are happy with CodeIgniter but you think some plug-ins and
        features should be provided by default.</li>
  <li>You want a simple and easy to learn framework that has 100%
       compatibility with CodeIgniter.</li>
  <li>You don\'t want to learn too many new terms.</li>
 <li>You are familiar with HMVC plugins, and you think it is one of
        \"should be exists\" feature in CodeIgniter.</li>
   <li>You are in tight deadline, at least you need to provide the
       prototype to your client.</li>
</ul>'),
  ('3', '1', 'No-CMS as CMS', 'no-cms-as-cms', '<p>
  No-CMS is a good enough CMS. It is different from Wordpress, Drupal or Joomla. Those CMS are built from developers for users. No-CMS is built by developer for developers, although everyone else can still use it as well. The main purpose of this CMS is to provide a good start of web application project, especially for CodeIgniter developer.</p>
'),
  ('4', '1', 'No-CMS as framework', 'no-cms-as-framework', '<p>No-CMS is not just another CMS. No-CMS allows you to make your own
  module and your own themes. This means that you (as developer) can make
   a module that can be used for several project.</p>

<p>No-CMS takes advantages of CodeIgniter as its core. It provides rich
 set of libraries for commonly needed task, as well as a simple
    interface and logical structure to access these libraries. The main
   advantage of CodeIgniter is you can creatively focus on your project by
   minimizing the amount of code needed or a given task.</p>

<p>No-CMS is also take advantages of several popular plugins such as</p>
<ul>
   <li>HMVC, to make fully modular separation</li>
   <li>Phil Sturgeon\'s Template, to make customizable themes</li>
   <li>groceryCRUD, to build CRUD application in a minute</li>
</ul>

<p>Out of all, No-CMS also provide some common features:</p>
<ul>
  <li>Authentication and Authorization by using group, privilege, and user
      management.
       <p>Not like other CMS, there is no backend-frontend in No-CMS. You
            have freedom to choose how different groups of users can access pages
         and modules differently.</p>
  </li>
 <li>Change Theme.
     <p>You can change the theme easily.</p>
   </li>
 <li>Install/Un-install Module
     <p>You can install/un-install module easily.</p>
  </li>
</ul>'),
  ('5', '1', 'Server Requirement', 'server-requirement', '<p>To install No-CMS, you should have these in your server :</p>
<ul>
    <li>PHP 5.3.2 or newer</li>
    <li>MySQL 5.0 or newer</li>
</ul>
<p>I recommend to use apache2 as web server. It seems that CodeIgniter is not doing well with nginx</p>'),
  ('6', '1', 'License', 'license', '<p>No-CMS has dual license</p>
<ul>
   <li>GPL</li>
  <li>MIT License</li>
</ul>
<p>In short, you can do everything to No-CMS, make money from it, share
  it with your friend, keep it in your disk, etc.</p>
<p>No-CMS is built with no warranty, but with best wishes. There will be
 no one responsible for any damage made by No-CMS</p>
<p>Please also consider, that this license is only applied to the CMS
   itself. Third party Modules and Themes are property of their creator.</p>'),
  ('7', '1', 'Credits', 'credits', '<p>I would like to thank all the contributors to the No-CMS project and
    you, the No-CMS user. Here are some names of considerable contributors:
</p>
<ul>
   <li>goFrendiAsgard : It\'s me, I am the one who make No-CMS based on
      CodeIgniter and some existing plug-ins.</li>
  <li>EllisLab : A company who make codeIgniter and make it available for
       free. There is no No-CMS without codeIgniter</li>
 <li>wiredesignz : He is the one who make HMVC plugin. The plug-in he
      made is known widely among CodeIgniter developer. It allowed me to
        make separation between modules</li>
  <li>Phil Sturgeon : He is the one who make CodeIgniter-template. The
      plugin he made allowed me to make separation between themes elements
      He is a member of CodeIgniter Reactor Engineer. His pyro-CMS also
     inspire me a lot (although I take different approach)</li>
    <li>John Skoumbourdis : He is the one who make groceryCRUD. It boost
      the development of No-CMS by provide very easy CRUD. He also give me
      some moral support to continue the development of No-CMS.</li>
    <li>Zusana Pudyastuti : She was my English Lecturer, A very good one
      who encourage me to speak English. It is a miracle for me to write
        this section in English :D</li>
   <li>Mukhlies Amien : He is one of my best friends. In this project, his
       role is advisor and tester.</li>
  <li>Gembong Edhi Setiawan : He is also one of my best friends. He gives
       some support and feature requests.</li>
   <li>Wahyu Eka Putra : He was my student. One of some best students in
     my class. He is the first one who discover a critical bug in the first
        stage of development.</li>
    <li>I Komang Ari Mogi : He is my classmate in my graduate program. He
     has some experience in design. That\'s why he can propose some fix in
     the very early stage of development.</li>
</ul>'),
  ('8', '2', 'Download No-CMS', 'download-no-cms', '<p>
    You can download No CMS from <a
       href=\"https://github.com/goFrendiAsgard/No-CMS\">No-CMS github
     repository</a>
</p>'),
  ('9', '2', 'Install No-CMS', 'install-no-cms', '<p>Installing No-CMS is very easy. You should provide :</p>
<ul>
   <li>Database Information
      <ul>
          <li>Database Server
               <p>It is about your database server name, it can be IP address or
                 computer\'s name If you install your database server is also your
                 web server, you can provide either \'localhost\' or \'127.0.0.1\' as
                  Database server</p>
           </li>
         <li>Port
              <p>In the current version of No-CMS we only support MySQL. The
                    default port for MySQL would be \'3306\'</p>
          </li>
         <li>Username
              <p>To use database, you must ensure that you are authorized to your
                   database server. For authorization sake, you should provide
                   username and password. The default value for the username is
                  \'root\'. If you use xampp, you can just keep this default value.</p>
         </li>
         <li>Password
              <p>The password to access database server, the default is blank,
                  means no password
         
          </li>
         <li>Database/Schema
               <p>The default database schema is \'no_cms\'. The installer will try
                  to make the datatabase schema if it is not exists yet</p>
         </li>
     </ul>
 </li>
 <li>Administrator Information
     <ul>
          <li>E mail
                <p>Fill it with your email account, it can be used for your
                   authentication</p>
            </li>
         <li>User name
             <p>Fill it with your desired user name, it will be used for your
                  authentication</p>
            </li>
         <li>Password
              <p>Fill it with your new password</p>
         </li>
     </ul>
 </li>
</ul>'),
  ('10', '3', 'User', 'user', ''),
  ('11', '3', 'Group', 'group', ''),
  ('12', '3', 'Navigation', 'navigation', ''),
  ('13', '3', 'Privilege', 'privilege', ''),
  ('14', '3', 'Module', 'module', ''),
  ('15', '3', 'Widget', 'widget', ''),
  ('16', '3', 'Theme', 'theme', ''),
  ('17', '3', 'Quick Link', 'quick-link', ''),
  ('18', '3', 'Configuration', 'configuration', ''),
  ('19', '4', 'Module Directory Structure', 'module-directory-structure', '<p>
    Making your own module is very easy if you are familiar with codeIgniter before. What you need to do is to make a directory inside modules directory of your No-CMS. So when you install No-CMS in <b>/var/www/No-CMS</b>, the modules directory will be <b>/var/www/No-CMS/modules</b> Every modules should contains at least 3 subdirectories. Those are</p>
<ul>
  <li>
      <b>models</b><br />
       This directory contains every models for a specific module</li>
   <li>
      <b>views</b><br />
        This directory contains every views for a specific module</li>
    <li>
      <b>controllers</b><br />
      This directory contains every controllers for a specific module</li>
</ul>
<p>
  For example, if you want to make <b>&quot;damn_simple_module&quot;</b>, you should make a directory named &quot;damn_simple_module&quot; in the modules directory. The directory structure would be like this:<br />
  <img src=\"modules/help/assets/images/modules_directory_structure.png\" style=\"float: left; margin: 10px; padding: 10px; \" /> Now, take a look at controllers directory. We should have at least 2 files there, i.e.:</p>
<ul>
 <li>
      <b>Main module controller file (in this case damn_simple_module.php)</b><br />
        This file contains a class which extends CMS_Controller. It must also have the same name as your module directory<br />
       <pre class=\"phpSnippet\">
    class Damn_simple_module extends CMS_Controller{
        //Your logic goes here.....
    }
</pre>
        For more detail about this file, please read Module&#39;s Controller section</li>
 <li>
      <b>Module installer file (in this case install.php)</b><br />
     A class which extends CMS_Module_Installer is needed in this file. Name the file as Install.php. Here you can specify what would be done when your module installed or un-installed. For more detail about this file, please read How to Make Your Module Install-able section</li>
</ul>
<p>
   Okay, now you can make as many module as you want. But you need to read the next section to know how to make a useful module :D</p>
'),
  ('20', '4', 'Module API', 'module-api', '<b>Model</b>
<p>
  To read this section you must be familiar with CodeIgniter MVC pattern. The models in No-CMS&#39;module are pretty similar with models in CodeIgniter. Whenever you make a model in CodeIgniter, you usually write this:</p>
<pre class=\"phpSnippet\">
    class My_Model extends CI_Model{
        //Your logic goes here.....
    }
</pre>
<p>
 If you are familiar with CodeIgniter, you must be familiear to the code above.</p>
<p>
   To make a module&#39;s model is quiet easy. Just as easy as make regular CodeIgniter Model. Remember to always put your module&#39;s models in <b>/modules/your_module_name/models</b>. You can gain some advantages by extend your model from CMS_Model instead of CI_Model. Some additional features such as get user name, user id, etc are already embedded in CMS_Model. To extend your model from CMS_Model, you can simply write:</p>
<pre class=\"phpSnippet\">
    class Damn_Simple_Module_Model extends CMS_Model{
        //Your logic goes here.....
    }
</pre>
'),
  ('21', '5', 'Theme Directory Structure', 'theme-directory-structure', '<p>
 No-CMS uses Phil Sturgeon&#39;s template as it&#39;s template engine. The advantage of using Phil&#39;s template is you can start with the big picture before go to details</p>
<p>
  <img src=\"modules/help/assets/images/No-CMS-layout.png\" style=\"float: right; margin: 10px; padding: 10px; width: 70%; \" /></p>
<p>
   One of the main idea behind No-CMS themes is to reduce verbosity of writing the same things over and over again. The header, widget, navigation link, and footer usually not changed that much. Only authorization differ navigation link appeared. By using such an approach you can focus on the content and left everything else to be done automatically by No-CMS</p>
<p>
   In No-CMS, a page is divided into several segment. These segment is called &quot;partials&quot;. Bassically there are header, footer, left, right, navigation_path and content partial. All of those partials except content are handled by No-CMS itself.</p>
<p>
   Making your costum themes is easy, but there are conventions that should be fullfilled. What you need to do is to make a directory inside themes directory of your No-CMS. So if you install No-CMS in <b>/var/www/No-CMS</b>, the modules directory will be <b>/var/www/No-CMS/themes</b>. Every themes should contains at least 3 subdirectories. Those are views, assets, and lib. Each are explained below :</p>
<ul>
    <li>
      <b>views</b><br />
        This is the most important directory. There should be 2 subdirectories here
       <ul>
          <li>
              <b>layouts</b><br />
              No-CMS will recognize your layouts based on everything you write here. You can have different layout for different device (e.g: desktop and mobile). Your client might also like to have &#39;admin&#39; and &#39;regular&#39; theme. In the most simple case, only default.php is required. But depended on requirement, you might also like to write some additional templates.
             <ul>
                  <li>
                      <b>default.php</b><br />
                      This is the basic and should be exists layout.</li>
                   <li>
                      <b>mobile.php</b><br />
                       This is the optional mobile layout. No-CMS uses user_agent to gain information about visitor&#39;s device. If the visitor uses mobile device (e.g: android smartphone), this layout will be activated.</li>
                   <li>
                      <b>default_backend.php</b><br />
                      This is the optional &#39;admin&#39; layout</li>
                  <li>
                      <b>mobile_backend.php</b><br />
                       This is the optional &#39;admin&#39; layout for mobile user</li>
              </ul>
         </li>
         <li>
              <b>partials</b><br />
             You should make some directory as much as your layout here. For example, if you have default.php and mobile.php in the layouts directory, then you should also have default and mobile sub-directories here. Each of those directories should consists of 5 files:
                <ul>
                  <li>
                      <b>header.php</b></li>
                    <li>
                      <b>footer.php</b></li>
                    <li>
                      <b>left.php</b></li>
                  <li>
                      <b>right.php</b></li>
                 <li>
                      <b>navigation_path.php</b></li>
               </ul>
             Those files are consists of header, footer, left, right, and navigation_path partial respectively.</li>
       </ul>
 </li>
 <li>
      <b>assets</b><br />
       This directory contains every static file that you want to use in your themes (e.g : javascript, css, images etc)</li>
    <li>
      <b>lib</b><br />
      This directory contains of some additional &quot;logics&quot; to show the theme correctly</li>
</ul>
'),
  ('22', '5', 'Theme API', 'theme-api', '<p>It is a good idea to check out Phil\'s template documentation. 
Bassically they are some variables you can use in your layout:
</p>
<ul>
  <li><b>$template[\'title\']</b><br />This is generated by Phil\'s template</li>
   <li><b>$template[\'partials\'][\'header\']</b><br />Generated by header.php in the respective partial</li>
    <li><b>$template[\'partials\'][\'footer\']</b><br />Generated by footer.php in the respective partial</li>
    <li><b>$template[\'partials\'][\'right\']</b><br />Generated by right.php in the respective partial</li>
  <li><b>$template[\'partials\'][\'left\']</b><br />Generated by left.php in the respective partial</li>
    <li><b>$template[\'partials\'][\'navigation_path\']</b><br />Generated by navigation_path.php in the respective partial</li>
  <li><b>$template[\'body\']</b><br />Your content</li>
 <li><b>$cms[\'site_name\']</b><br />Site name from the configuration</li>
    <li><b>$cms[\'site_slogan\']</b><br />Site slogan from the configuration</li>
    <li><b>$cms[\'site_footer\']</b><br />Site footer from the configuration</li>
    <li><b>$cms[\'site_theme\']</b><br />Site theme from the configuration</li>
    <li><b>$cms[\'site_logo\']</b><br />Site logo from the configuration</li>
    <li><b>$cms[\'site_favicon\']</b><br />Site favicon from the configuration</li>
    <li><b>$cms[\'navigations\']</b><br />Navigations in array format, need some logic to show it in an appropriate way</li>
    <li><b>$cms[\'navigation_path\']</b><br />Navigation path, need some logic to show it in an appropriate way</li>
    <li><b>$cms[\'widget\']</b><br />Widget, need some logic to show it in an appropriate way</li>
    <li><b>$cms[\'user_name\']</b><br />Current user name</li>
    <li><b>$cms[\'quicklinks\']</b><br />Quick Links, need some logic to show it in an appropriate way</li>
    <li><b>$cms[\'module_name\']</b><br />Current module name</li>           
</ul>
<p>The $cms variables can also be used in the partials as well
</p>
<p>This is an example default.php content:</p>
<pre class=\"htmlSnippet\">
    &lt;html&gt;
        &lt;head&gt;
          &lt;title&gt;&lt;?php echo $template[\'title\'];?&gt;&lt;/title&gt;
           &lt;link rel=\"icon\" href=\"&lt;?php echo $cms[\'site_favicon\'];?&gt;\"&gt;
         &lt;script type=\"text/javascript\" src =\"&lt;?php echo base_url().\'assets/nocms/js/jquery.js\';?&gt;\"&gt;&lt;/script&gt;
          &lt;link rel=\"stylesheet\" type=\"text/css\" href=\"&lt;?php echo base_url().\"themes/\".$cms[\'site_theme\'].\"/assets/default/style.css\";?&gt;\"&gt;&lt;/link&gt;
           &lt;script type=\"text/javascript\" src=\"&lt;?php echo base_url().\"themes/\".$cms[\'site_theme\'].\"/assets/default/script.js\";?&gt;\"&gt;&lt;/script&gt;
      &lt;/head&gt;
     &lt;body&gt;       
           
          &lt;div id=\"layout_header\"&gt;&lt;?php echo $template[\'partials\'][\'header\'];?&gt;&lt;/div&gt;
         
          &lt;div id=\"layout_center\"&gt;
                &lt;div id=\"layout_right\"&gt;&lt;?php echo $template[\'partials\'][\'right\'] ?&gt;&lt;/div&gt;
               &lt;div id=\"layout_content\"&gt;
                   &lt;div id=\"layout_nav_path\"&gt;You are here : &lt;?php echo $template[\'partials\'][\'navigation_path\']?&gt;&lt;/div&gt;
                    &lt;br /&gt;
                  &lt;?php echo $template[\'body\'];?&gt;
               &lt;/div&gt;
              &lt;div class=\"layout_clear\"&gt;&lt;/div&gt;
          &lt;/div&gt;
          
          &lt;div id=\"layout_footer\"&gt;&lt;?php echo $template[\'partials\'][\'footer\'];?&gt;&lt;/div&gt; 
        &lt;/body&gt;
 &lt;/html&gt;
</pre>
<p>While this is an example header.php partials:</p>
<pre class=\"htmlSnippet\">
    &lt;?php require_once BASEPATH.\"../themes/\".$cms[\'site_theme\'].\"/lib/function.php\";?&gt;
    &lt;img class=\"layout_float_left\" src =\"&lt;?php echo $cms[\'site_logo\'];?&gt;\" /&gt;
    &lt;div class=\"layout_float_left layout_large_left_padding\"&gt;
       &lt;h1&gt;&lt;?php echo $cms[\'site_name\'];?&gt;&lt;/h1&gt;
        &lt;h2&gt;&lt;?php echo $cms[\'site_slogan\'];?&gt;&lt;/h2&gt;
        &lt;?php echo build_quicklink($cms[\'quicklinks\']);?&gt;
    &lt;/div&gt;
    &lt;div class=\"layout_clear\"&gt;&lt;/div&gt;
</pre>
<p>I hope you can get the idea :)</p>'),
  ('23', '6', 'Using Module Generator', 'using-module-generator', '<p>
  Using module generator is easily straight-forward.</p>
<p>
   First of all, please make sure that your module directory is writable. Next, you should provide unique namespace to your application, and module directory name. Please make sure that your module directory name is not already exists. You can then choose tables from your database (beware to not choose something with cms prefix, except you have a good reason for it)</p>
<p>
    Your module will contains of basic CRUD skeleton (which is built nicely by using groceryCRUD) and an installation script. By default, the installation script will drop every table you&#39;ve select in the previous step, and create it again. Also, it will generate some navigations.</p>
<p>
    Now you have a working module prototype. Edit it as you wish <img alt=\"smiley\" height=\"20\" src=\"http://localhost/~gofrendi/No-CMS/assets/grocery_crud/texteditor/ckeditor/plugins/smiley/images/regular_smile.gif\" title=\"smiley\" width=\"20\" /></p>
'),
  ('24', '7', 'Change default controller', 'change-default-congroller', '<p>
    To change the default controller, you can modify&nbsp;<span style=\"background-color: rgb(247, 247, 249); color: rgb(221, 17, 68); font-family: Menlo, Monaco, Consolas, \'Courier New\', monospace; line-height: 18px; \">$route[&#39;default_controller&#39;]</span>&nbsp;variable, at&nbsp;<span style=\"background-color: rgb(247, 247, 249); color: rgb(221, 17, 68); font-family: Menlo, Monaco, Consolas, \'Courier New\', monospace; line-height: 18px; \">/application/config/routes.php</span>&nbsp;around line 41.</p>
<p>
    For example, you want &quot;blog&quot; to be your default controller, you can simply change the variable into:</p>
<p>
   <span style=\"color: rgb(221, 17, 68); font-family: Menlo, Monaco, Consolas, \'Courier New\', monospace; line-height: 18px; background-color: rgb(247, 247, 249); \">$route[&#39;default_controller&#39;] = &quot;blog/blog&quot;;</span></p>
'),
  ('25', '8', 'Why Open Source?', 'why-open-source', '<p>
   There are three reasons why No-CMS is free and open source:</p>
<p>
  <strong>Good reason:</strong> No-CMS is open source, because I want to share it to everyone. I know the pain every web developer bear, and I want something that can make developer&#39;s life easier. Also, open source means everyone involved, so that many ideas will be raised in the development progress.</p>
<p>
 <strong>Technical reason:</strong> Finding bug is disgusting. But, more people use it, more bug will be discovered faster, Also, it will be helpful if I don&#39;t debug everything by myself.</p>
<p>
   <strong>Fun reason:</strong> I want to push myself to the limit. See if my programming skill is good enough. By develop No-CMS, I can learn as much as I want without any limitation.</p>
'),
  ('26', '8', 'Why another CMS?', 'Why-another-CMS', '<p>
   There are so many excellent open source CMS out there. They have good features. Some of them have modular extensions, and even form generator. Many of them have multi-language support, and it is a very good features.</p>
<p>
 But for me, there is no other CMS can be as fit as No-CMS. I think it is the same reason why Yukihiro Matsumoto make ruby language.</p>
');
