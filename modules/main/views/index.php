<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h3>Welcome {{ user_name }}</h3>
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
					$module_path = $module['module_path'];
					$controllers = $module['controllers'];
					foreach($controllers as $controller){
						echo '<li><code>$route[\'default_controller\'] = "'.
								$module_path.'/'.$controller.'";</code></li>';
					}					
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

<p class="well span9">
	<b>Note : </b><br />
	CodeIgniter forum member can visit  No-CMS thread here:  <a href="http://codeigniter.com/forums/viewthread/209171/">http://codeigniter.com/forums/viewthread/209171/</a><br />
	Github user can visit No-CMS repo:  <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />
	While normal people can visit No-CMS blog: <a href="http://www.getnocms.com/">http://www.getnocms.com/</a><br />
	In case of you've found a critical bug, you can also email me at <a href="mailto:gofrendiasgard@gmail.com">gofrendiasgard@gmail.com</a><br />
	That's all. Start your new adventure with No-CMS !!!
</p>