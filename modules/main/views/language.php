<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h3>Language</h3>
<?php
	echo '<br />';
	foreach($language_list as $language){
		echo anchor(site_url($cms['module_path'].'/language/'.$language), $language);
		echo br();
	}
?>