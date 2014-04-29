<?php
$available_site = array();
$site_alias = array();

/* To add additional sub-site,
  1. Copy /application/config content to /application/config-your_site:
  2. Modify database configuration, manually create database
  3. Modify $available_site and $site_alias
*/

// If you want a sub-site named "dragon" (Can be accessed by both url: http://dragon.domain.com, or http://domain.com/site-dragon), uncomment this:
// $available_site[] = 'dragon';
// If you want the "dragon" subsite can be accessed through different URL (you have parked domain) uncomment this
// $site_alias['other_domain.com'] = 'dragon';

