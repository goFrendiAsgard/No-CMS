
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">
		::selection {
			background-color:#E13300;
			color:#fff;
		}

		::moz-selection {
			background-color:#E13300;
			color:#fff;
		}

		::webkit-selection {
			background-color:#E13300;
			color:#fff;
		}

		body {
			background-color:#fff;
			margin:40px;
			font:13px/20px normal Helvetica,Arial,sans-serif;
			color:#4F5155;
		}

		a {
			color:#039;
			background-color:transparent;
			font-weight:400;
		}

		h1 {
			color:#444;
			background-color:transparent;
			border-bottom:1px solid #D0D0D0;
			font-size:19px;
			font-weight:400;
			margin:0 0 14px;
			padding:14px 15px 10px;
		}

		code,pre,fieldset {
			font-family:Consolas,Monaco,Courier New,Courier,monospace;
			font-size:12px;
			border:1px solid #D0D0D0;
			color:#002166;
			display:block;
			margin:10px 0 14px;
			padding:12px 10px;
		}

		fieldset {
			margin:10px 14px;
			overflow:auto;
		}

		#body {
			margin:0 15px;
		}

		p.footer {
			border-top:1px solid #D0D0D0;
			padding:10px 10px 0;
			margin:20px 0 0;
		}

		#container {
			margin:10px;
			border:1px solid #D0D0D0;
			-webkit-box-shadow:0 0 8px #D0D0D0;
		}

		.key {
			border:1px solid #000;
			border-right:none;
			border-bottom:0;
		}

		.value {
			border:1px solid #000;
			border-left:none;
			border-bottom:0;
		}

		#provider-list {
			list-style:none;
			margin:0;
			padding:0;
		}

		#provider-list a {
			position:relative;
			width:auto;
			float:left;
			margin:0 10px;
			padding:5px 10px;
			border:1px solid #eee;
			background:#f8f8f8;
		}

		#provider-list a.connected {
			padding-left:35px;
		}

		#provider-list a.connected:before {
		/*fill it with a blank space*/
			content:"\00a0";
		/*make it a block element*/
			display:block;
		/*adding an 8px round border to a 0x0 element creates an 8px circle*/
			border:solid 9px green;
			border-radius:9px;
			-moz-border-radius:9px;
			-webkit-border-radius:9px;
			height:0;
			width:0;
		/*Now position it on the left of the list item, and center it vertically
				(so that it will work with multiple line list-items)*/
			position:absolute;
			left:7px;
			top:47%;
			margin-top:-8px;
		}

		#provider-list a.connected:hover:before {
			border-color:#000;
		}

		#provider-list a.connected:after {
		/*Add another block-level blank space*/
			content:"\00a0";
			display:block;
		/*Make it a small rectangle so the border will create an L-shape*/
			width:3px;
			height:6px;
		/*Add a white border on the bottom and left, creating that 'L' */
			border:solid #fff;
			border-width:0 2px 2px 0;
		/*Position it on top of the circle*/
			position:absolute;
			left:14px;
			top:47%;
			margin-top:-4px;
		/*Rotate the L 45 degrees to turn it into a checkmark*/
			-webkit-transform:rotate(45deg);
			-moz-transform:rotate(45deg);
			-o-transform:rotate(45deg);
		}

		#provider-list a.connected:hover:after {
			content:"\D7";
			display:block;
			text-align:center;
			width:18px;
			position:absolute;
			left:7px;
			top:40%;
			margin-top:-8px;
			font-size:16px;
			line-height:18px;
			font-family:"Helvetica Neue",Consolas,Verdana,Tahoma,Calibri,Helvetica,Menlo,"Droid Sans",sans-serif;
			border:0;
			-webkit-transform:rotate(0deg);
			-moz-transform:rotate(0deg);
			-o-transform:rotate(0deg);
			color:#f8f8f8;
			box-shadow:none;
		}

		#provider-list li {
		}

		#provider-list a:hover,#provider-list .login:hover a:link,#provider-list .login:hover a:visited {
			background:#C64350;
			color:#F6CF74;
			text-shadow:1px 1px 1px #fff;
		}

		#provider-list a {
			text-decoration:none;
			display:block;
		}

		.pItem {
			list-style:none;
			font-size:1.15em;
			line-height:1.18em;
			padding:5px;
			margin:3px 0;
			color:#444;
			border:1px solid #e8e8e8;
			background-color:#f9f9f9;
			white-space:pre;
		/* CSS 2.0 */
			white-space:pre-wrap;
		/* CSS 2.1 */
			white-space:pre-line;
		/* CSS 3.0 */
			white-space:-pre-wrap;
		/* Opera 4-6 */
			white-space:-o-pre-wrap;
		/* Opera 7 */
			white-space:-moz-pre-wrap;
		/* Mozilla */
			white-space:-hp-pre-wrap;
		/* HP Printers */
			word-wrap:break-word;
		/* IE 5+ */
		}

		.pItem:hover {
			background:#ffc;
		}
	</style>
</head>
<body>

<div id="container">
	<h1>Welcome to HybridIgniter</h1>

	<div id="body">
		<p>We've brought together CodeIgniter and Hybrid auth for the best solution for user authentication.</p>
		<p>Select a service to authenticate with. If you have previously authenticated, it will be denoted below.</p>
		<h4>Select a service:</h4>
		<ul id="provider-list">
		<?php
			// Output the enabled services and change link/button if the user is authenticated.
			$this->load->helper('url');
			foreach($providers as $provider => $data) {
				if ($data['connected']) {
					echo "<li>".anchor('hauth/logout/'.$provider,'Logout of '.$provider, array('class' => 'connected'))."</li>";
				} else {
					echo "<li>".anchor('hauth/login/'.$provider,$provider, array('class' => 'login'))."</li>";
				}
			}
		?>
		</ul>
		<br style="clear: both;"/>

	</div>
	<p class="footer">
	<?
	// Output the profiles of each logged in service
	foreach ($providers as $provider => $d) :
		if (!empty($d['user_profile'])) :
			$profile[$provider] = (array)$d['user_profile'];
			?>
			<fieldset>
	        <legend><strong><?=$provider?></strong> Profile</legend>
	        <table width="100%">
	          <tr>
	            <td width="150" valign="top" align="center">
					<?php
						if( !empty($d['user_profile']->profileURL) ){
					?>
						<a href="<?php echo $d['user_profile']->profileURL; ?>"><img src="<?php echo $d['user_profile']->photoURL; ?>" title="<?php echo $d['user_profile']->displayName; ?>" border="0" style="height: 120px;"></a>
					<?php
						}
						else{
					?>
					<img src="public/avatar.png" title="<?php echo $d['user_profile']->displayName; ?>" border="0" >
					<?php
						}
					?>
				</td>
	            <td align="left"><table width="100%" cellspacing="0" cellpadding="3" border="0">
	                <tbody>
					<?
					foreach ($d['user_profile'] as $key=>$value) :
						if ($value =="") {
							continue;
						}
					?>
	                  <tr>
	                  	<td class="pItem"><strong><?=ucfirst($key)?>:</strong> <?=(filter_var($value, FILTER_VALIDATE_URL) !== false) ?  '<a href="'.$value.'" target="_blank">'.$value.'</a>' : $value;?></td>
	                  </tr>
					<? endforeach; ?>
	                </tbody>
	              </table>
				  </td>
	          </tr>
	        </table>
	      </fieldset>
		<?
		endif;
	endforeach;
	?>
	</p>
</div>
</body>
</html>


