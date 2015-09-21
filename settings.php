<?php

global $settings;

$settings = array();
$settings['debug'] = true;
$settings['safemode_url'] = '';  //url of your safe mode script
$settings['sfstore'] = 'sfstore/';  //directory to store your backup files 
$settings['wp_dir'] = '../';  //directory path to your wordpress site, not url, add trailing slash, only change this if wpsafemode tool is not in root of your WordPress website
$settings['safemode_dir'] = str_replace('\\','/',dirname(__FILE__)) . '/'; //don't touch this


$settings['view_url'] = 'view/'; //don't touch this

include_once(  $settings['wp_dir'] . 'wp-config.php'); //don't touch this
$settings['wp_db_prefix'] = $table_prefix; //don't touch this