<?php include 'modules/help/assets/toc_include.php' ?>
<h3>Module's Model</h3>
<p>
    To read this section you must be familiar with CodeIgniter MVC pattern.
    The models in Neo-CMS'module are pretty similar with models in CodeIgniter.
    Whenever you make a model in CodeIgniter, you usually write this:
<pre class="phpSnippet">
    class My_Model extends CI_Model{
        //Your logic goes here.....
    }
</pre>
    If you are familiar with CodeIgniter, you must be use to the code above.
    Remember to always put the model files in /modules/your_module_name/models.
    To gain advantages of using some additional features (such as get current user id, get current user name, etc).
<pre class="phpSnippet">
    class Damn_Simple_Module_Model extends CMS_Model{
        //Your logic goes here.....
    }
</pre>
    It is easy, right?
</p>
<p>
    I just mention that there are additional features when your model extends CMS_Model. Here they are:
    <ul>
        <li>cms_user_id()</li>
        <li>cms_user_name()</li>
    </ul>
    (Sorry, I should go with my lecture,... I'll finish it later :D)
</p>
