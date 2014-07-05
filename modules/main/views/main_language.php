<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h3>Language</h3>
<?php
	echo '<ul>';
	foreach($language_list as $language){
	    if($language->code == $current_language){
	        echo '<li>'.$language->name.'</li>';
	    }else{
		  echo '<li>'.anchor(site_url('{{ module_path }}/language/'.$language->code), $language->name).'</li>';
        }
	}
    echo '</ul>';
