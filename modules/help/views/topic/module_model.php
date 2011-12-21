<h3>Module's Model</h3>
<p>
    To read this section you should be familiar with CodeIgniter MVC pattern.
    The models in Neo-CMS'module are pretty similar with models in CodeIgniter.
    Whenever you make a model in CodeIgniter, you usually write this:
<code>
<pre>
    class My_Model extends CI_Model{
        //Your logic goes here.....
    }
</pre>
</code>
    If you are familiar with CodeIgniter, you should have been familiar with code above.
    To make a module in Neo-CMS (you should put the files in /modules/your_module_name/models),
    you can make a simple CI Model (just as above). But for additional feature (such as get current user id etc)
    your model should extends CMS_Model.
<code>
<pre>
    class Damn_Simple_Module_Model extends CMS_Model{
        //Your logic goes here.....
    }
</pre>
</code>
    It is easy, right?
</p>
<p>
    I just mention that there are additional feature when you use CMS_Model. Here they are:
    <ul>
        <li>cms_user_id</li>
    </ul>
    (Sorry, I should go with my lecture,... I'll finish it later :D)
</p>
