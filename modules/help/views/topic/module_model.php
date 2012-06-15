<?php include 'modules/help/assets/toc_include.php' ?>
<h3>Module's Model</h3>
<p>To read this section you must be familiar with CodeIgniter MVC
	pattern. The models in No-CMS'module are pretty similar with models in
	CodeIgniter. Whenever you make a model in CodeIgniter, you usually
	write this:</p>
<pre class="phpSnippet">
    class My_Model extends CI_Model{
        //Your logic goes here.....
    }
</pre>
<p>
If you are familiar with CodeIgniter, you must be familiear to the code above.
</p>
<p>
To make a module's model is quiet easy. Just as easy as make regular CodeIgniter Model.
Remember to always put your module's models in <b>/modules/your_module_name/models</b>. 
You can gain some advantages by extend your model from CMS_Model instead of CI_Model.
Some additional features such as get user name, user id, etc are already embedded in CMS_Model.
To extend your model from CMS_Model, you can simply write:
</p>
<pre class="phpSnippet">
    class Damn_Simple_Module_Model extends CMS_Model{
        //Your logic goes here.....
    }
</pre>
<p>It is easy, right?</p>
<p>TODO: write about some additional functions</p>
