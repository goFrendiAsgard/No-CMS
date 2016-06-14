<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2013, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to No-CMS</title>
    <style type="text/css">
        ::selection{ background-color: #E13300; color: white; }
        ::moz-selection{ background-color: #E13300; color: white; }
        ::webkit-selection{ background-color: #E13300; color: white; }

        body {
            padding-top: 10px;
            padding-bottom: 5px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>" />

</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <img src="<?php echo base_url('assets/nocms/images/No-CMS-logo.png') ?>" style="float:left; margin-right:10px;" />
            <h1>Welcome to No-CMS</h1>
            <h2>A Free CodeIgniter Based CMS Framework</h2>
            <p> You can <a class="btn btn-primary" href="<?php echo site_url('installer/index'); ?>"><b>install No-CMS</b></a> anytime you are ready to</p>
        </div>

        <div class="well row-fluid col-sm-12">
            <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

            <p>If you would like to edit this page you'll find it located at:</p>
            <code>application/views/welcome_message.php</code>

            <p>The corresponding controller for this page is found at:</p>
            <code>application/controllers/welcome.php</code>

            <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="<?php echo base_url('ci_user_guide') ?>">CodeIgniter User Guide</a>.</p>
        </div>

        <p class="footer well row-fluid col-sm-12">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
    </div>
    <script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/'.JQUERY_FILE_NAME);?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>

</body>
</html>
