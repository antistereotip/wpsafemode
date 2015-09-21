<?php
/**
* 
* Wordpress safe mode v0.04 beta 
* authors: CloudIndustry - http://cloud-industry.com 
 Nikola Kirincic, Milutin Gavrilovic,  Marko Tiosavljevic,   Nikola Stojanovic
  For more information about installation, usage, licensing and other notes see README 
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if(function_exists('session_status') && session_status() == PHP_SESSION_NONE){
	session_start();
}else{
	if(session_id() == '') {
    session_start();
    }
}

include_once 'controller/dashboard.controller.php';