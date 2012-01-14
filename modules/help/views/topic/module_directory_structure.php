<?php include 'modules/help/assets/toc_include.php' ?>
<h3>Module Directory Structure</h3>
<p>
    Making your own module is very easy if you are familiar with codeIgniter before.
    What you need to do is to make a directory inside modules directory of your Neo-CMS.
    So when you install Neo-CMS in <b>/var/www/Neo-CMS</b>, 
    the modules directory will be <b>/var/www/Neo-CMS/modules</b> 
    Every modules should contains at least 3 subdirectories. Those are
    <ul>
        <li><b>models</b><br />
            This directory contains every models for a specific module
        </li>
        <li><b>views</b><br />
            This directory contains every views for a specific module
        </li>
        <li><b>controllers</b><br />
            This directory contains every controllers for a specific module
        </li>
    </ul>
</p>
<p>
    For example, if you want to make <b>"damn_simple_module"</b>,
    you should make a directory named "damn_simple_module" in the modules directory. The directory structure would be like this:<br />
    <img src="<?php echo base_url();?>modules/help/assets/images/modules_directory_structure.png" style="float:left;margin:10px;padding:10px"/>
    Now, take a look at controllers directory. We should have at least 2 files there, i.e.:
    <ul>
        <li><b>Main module controller file (in this case damn_simple_module.php)</b><br />
            This file contains a class which extends CMS_Controller. 
            It must also have the same name as your module directory<br />

<pre class="phpSnippet">
    class Damn_simple_module extends CMS_Controller{
        //Your logic goes here.....
    }
</pre>

            For more detail about this file, please read Module's Controller section
        </li>
        <li><b>Module installer file (in this case install.php)</b><br />
            A class which extends CMS_Module_Installer is needed in this file.
            Name the file as Install.php. Here you can specify what would be done when your module installed or un-installed.
            For more detail about this file, please read How to Make Your Module Install-able section
        </li>
    </ul>
    Okay, now you can make as many module as you want. 
    But you need to read the next section to know how to make a useful module :D
</p>