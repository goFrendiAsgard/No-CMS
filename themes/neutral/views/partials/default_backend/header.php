<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<img src ="<?php echo base_url().'assets/nocms/images/No-CMS.png';?>" />
<h2><?php echo $site_name;?> - <?php echo $site_slogan;?></h2>
<?php echo build_quicklink($quicklinks);?>