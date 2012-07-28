<?php include 'modules/help/assets/toc_include.php' ?>
<h3>Themes Directory Structure</h3>

<p>
	No-CMS uses Phil Sturgeon's template as it's template engine.
	The advantage of using Phil's template is you can start with the big picture
	before go to details
</p>
<img src="<?php echo base_url();?>modules/help/assets/images/No-CMS-layout.png"
					style="float: right; margin: 10px; padding: 10px; width: 70%" />
<p>
	One of the main idea behind No-CMS themes is to reduce verbosity of writing the same
	things over and over again. The header, widget, navigation link, and footer usually not changed that much.
	Only authorization differ navigation link appeared. 
	By using such an approach you can focus on the content and left everything else to be done automatically by No-CMS
</p>
<p>
	In No-CMS, a page is divided into several segment. These segment is called "partials". 
	Bassically there are header, footer, left, right, navigation_path and content partial.
	All of those partials except content are handled by No-CMS itself. 
</p>
<p>
	Making your costum themes is easy, but there are conventions that should be fullfilled.
	What you need to do is to make a directory inside themes directory of your No-CMS. 
	So if you install No-CMS in <b>/var/www/No-CMS</b>,
	the modules directory will be <b>/var/www/No-CMS/themes</b>. Every
	themes should contains at least 3 subdirectories. Those are views, assets, and lib. Each are explained below :
</p>
<ul>
	<li><b>views</b><br /> This is the most important directory. There should be 2 subdirectories here
		<ul>
			<li><b>layouts</b><br /> No-CMS will recognize your layouts based on everything you write here.
				You can have different layout for different device (e.g: desktop and mobile). Your client might also like to have 'admin' and 'regular' theme.
				In the most simple case, only default.php is required. But depended on requirement, you might also like to write some additional templates.
				<ul>
					<li><b>default.php</b><br />This is the basic and should be exists layout.</li>
					<li><b>mobile.php</b><br />This is the optional mobile layout. No-CMS uses user_agent to gain information about visitor's device.
						If the visitor uses mobile device (e.g: android smartphone), this layout will be activated.
					</li>
					<li><b>default_backend.php</b><br />This is the optional 'admin' layout</li>
					<li><b>mobile_backend.php</b><br />This is the optional 'admin' layout for mobile user</li>
				</ul>			
			</li>
			<li><b>partials</b><br />				
				You should make some directory as much as your layout here.
				For example, if you have default.php and mobile.php in the layouts directory, then you should also have
				default and mobile sub-directories here. Each of those directories should consists of 5 files:				
				<ul>
					<li><b>header.php</b><br /></li>
					<li><b>footer.php</b><br /></li>
					<li><b>left.php</b><br /></li>
					<li><b>right.php</b><br /></li>
					<li><b>navigation_path.php</b><br /></li>
				</ul>
				Those files are consists of header, footer, left, right, and navigation_path partial respectively.				
			</li>
		</ul>
	</li>
	<li><b>assets</b><br /> This directory contains every static file that you want to use in your themes (e.g : javascript, css, images etc)</li>
	<li><b>lib</b><br /> This directory contains of some additional "logics" to show the theme correctly</li>
</ul>