<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
    body{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    input{
        margin-bottom: 15px;
    }
    label{
        font-weight: bold;
    }
    button{
        color: #fff;
        background-color: #428bca;
        border-color: #357ebd;
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    p{
        text-align: justify;;
    }
    .description{
        font-size: small;
    }
    .quote{
        font-style: italic;
    }
    .error{
        padding: 10px;
        margin-top: 20px;
        margin-bottom:10px;
        color: #aa0000;
        background-color: #f2a1c3;
    }
    .div-left{
        vertical-align: middle;
        display: inline-block;
        width: 40%;
    }
    img#logo{
        width: 90%;
    }
</style>

<div class="div-left">
    <img id="logo" src="<?php echo base_url('assets/nocms/images/No-CMS-logo.png') ?>" />
</div>

<div class="div-left">
    <h1><?php echo $title; ?></h1>
    <p class="quote"><?php echo $quote; ?></p>

    <p class="description"><?php echo $description1 . $description2 . $description3 . $description4; ?></p>

    <?php if($user_name != ''){ ?>
        <p class="error"><b><?php echo $error1; ?></b> <?php echo $error2; ?></p>
    <?php } ?>

    <form method="post">
        <label><?php echo $label_user; ?></label><br />
        <input name="<?php echo $input_user_name; ?>" value="<?php echo $user_name; ?>" /><br />
        <label><?php echo $label_password; ?></label><br />
        <input name="<?php echo $input_password; ?>" type="password" /><br />
        <button name="reset"><?php echo $label_button; ?></button>
    </form>
</div>
