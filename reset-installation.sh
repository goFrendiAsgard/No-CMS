#!/bin/bash
sudo chmod 777 . -R
sudo rm -f ./application/config/*.php
sudo rm -f ./application/logs/log*.php
sudo rm -f ./application/logs/hybridauth.log
sudo touch ./application/logs/hybridauth.log
sudo rm -f ./assets/grocery_crud/texteditor/ckeditor/config.js
sudo rm -f ./assets/kcfinder/config.php
sudo rm -f ./.htaccess
sudo rm -f ./site.php
sudo chmod 777 . -R
