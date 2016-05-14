#!/bin/bash
sudo chmod 777 . -R
sudo rm -f ./application/config/*.php
sudo echo "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');" > ./application/config/constants.php
sudo rm -Rf ./session
sudo rm -f ./application/logs/log*.php
sudo rm -f ./application/logs/hybridauth.log
sudo touch ./application/logs/hybridauth.log
sudo rm -f ./application/config/tmp/*.php
sudo rm -f ./assets/grocery_crud/texteditor/ckeditor/config.js
sudo rm -f ./assets/kcfinder/config.php
sudo rm -Rf ./assets/kcfinder/upload/main-*
sudo rm -Rf ./assets/kcfinder/upload/site-*
sudo rm -f ./.htaccess
sudo rm -f ./hostname.php
sudo rm -Rf ./application/config/site-*
sudo rm -Rf ./application/config/main
sudo rm -f ./modules/*/config/module_config_*.php
sudo rm -f modules/*/controllers/Info_*.php
sudo chmod 777 . -R
