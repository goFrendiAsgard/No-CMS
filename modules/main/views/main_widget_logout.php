<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo $welcome_lang;?>, <?php echo $user_name; ?><br />
<?php echo anchor('main/logout', $logout_lang);