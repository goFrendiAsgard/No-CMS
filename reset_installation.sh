#!/bin/bash
sudo chmod 777 . -R
sudo rm -f ./application/config/.saved
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
sudo chmod 777 .
sudo chmod 755 * -R
sudo chmod 777 application/config -R
sudo chmod 777 application/logs -R
sudo chmod 755 assets/kcfinder -R
sudo chmod 777 assets/kcfinder
sudo chmod 777 assets/kcfinder/upload
sudo chmod 755 assets/kcfinder/upload/.htaccess
sudo chmod 755 assets/kcfinder/upload/index.html
sudo chmod 755 assets/grocery_crud/texteditor/ckeditor -R
sudo chmod 777 assets/grocery_crud/texteditor/ckeditor
sudo chmod 777 assets/uploads -R
sudo chmod 755 assets/uploads/index.html
sudo chmod 755 assets/uploads/.htaccess
sudo chmod 777 assets/nocms/images -R
sudo chmod 644 assets/nocms/images/*.png
sudo chmod 755 assets/nocms/images/*/.htaccess
sudo chmod 755 assets/nocms/images/*/index.html
sudo chmod 777 modules
sudo chmod 777 modules/*/assets/uploads -R
sudo chmod 755 modules/*/assets/uploads/.htaccess
sudo chmod 755 modules/*/assets/uploads/index.html
sudo chmod 644 modules/*/assets/uploads/*.jpg
sudo chmod 644 modules/*/assets/uploads/*.png
sudo chmod 777 modules/*/assets/db -R
sudo chmod 755 modules/*/controllers -R
sudo chmod 777 modules/*/controllers
sudo chmod 777 modules/*/config
sudo chmod 777 modules/artificial_intelligence/assets/data -R
sudo chmod 666 modules/artificial_intelligence/assets/data/1_*_Default
