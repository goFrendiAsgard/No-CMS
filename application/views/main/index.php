<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$ci =& get_instance();
	$module_list = $ci->cms_get_module_list();
?>
<h3>Home</h3>
<p>
	This is the home page. You have several options to modify this page.
		
</p>
<ul>
	<li><b>Using static page</b>
		<p>
			You can activate static option and edit the static content by using
			<a href="<?php echo site_url('main/navigation/edit/16'); ?>">Navigation Management</a><br />
			This is the most recommended way to do.
		</p>		
	</li>
	<li><b>Redirect default controller</b>
		<p>
			You can modify <code>$route['default_controller']</code> variable on<br /> 
			<code>/application/config/routes.php</code>, around line 41.<br />
			Please make sure that your default controller is valid.<br />
			Here are some possible values:<br />
		</p>
		<ul>
		<?php 
			foreach($module_list as $module){
				if($module['installed']){
					echo '<li><code>$route[\'default_controller\'] = "'.
						$module['module_path'].'";</code></li>';
				}
			}
		?>
		</ul>
		<p>	
			This is recommended if you also want your own page to be a default homepage.			
		</p>
	</li>
	<li><b>Edit the view manually</b>
		<p>
			You can edit the corresponding view on <code>/modules/main/index.php</code><br />
			This is not recommended, since you may possibly lost this original home page.
		</p>
	</li>	
</ul>