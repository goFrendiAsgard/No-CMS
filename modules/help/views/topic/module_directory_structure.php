<h3>Module Directory Structure</h3>
<p>
    Making your own module is damn easy (if you are familiar with codeIgniter before)
    What you need to do is make a directory inside modules directory of your Neo-CMS.
    So if you install Neo-CMS in <b>/var/www/Neo-CMS</b>, 
    the modules directory would be <b>/var/www/Neo-CMS/modules</b> 
    Every modules should contains at least 3 subdirectory. Those are
    <ul>
        <li><b>models</b><br />
            This directory would contains every models for a specific module
        </li>
        <li><b>views</b><br />
            This directory would contains every views for a specific module
        </li>
        <li><b>controllers</b><br />
            This directory would contains every controllers for a specific module
        </li>
    </ul>
</p>
<p>
    For example if you want to make <b>"damn_simple_module"</b>
    You should make a directory named "damn_simple_module" inside modules directory. The directory structure would be like this:<br />
    <img src="<?php echo base_url();?>modules/help/assets/images/modules_directory_structure.png" style="float:left;margin:10px;padding:10px"/>
    Got it? Now, take a good look at controllers directory. We should have at least 2 files there:
    <ul>
        <li><b>Main module controller file (damn_simple_module.php)</b><br />
            This file should contains a class which extends CMS_Controller. 
            It should also has the same name as your module directory<br />
<code>
<pre>
    class Damn_simple_module extends CMS_Controller{
        //Your logic goes here.....
    }
</pre>
</code>
            For more detail about this file, please read Module's Controller section
        </li>
        <li><b>Module installer file (install.php)</b><br />
            This file should contains a class which extends CMS_Module_Installer.
            The name should be Install.php. Here you can specify what would be done when your module installed or un-installed.
            For more detail about this file, please read How to Make Your Module Install-able section
        </li>
    </ul>
    Okay, now you can make as many module as you want. 
    But you need to read the next section to know how to make a useful module :D
</p>