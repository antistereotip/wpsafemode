<?php


include_once('settings.php');
 class dbModel extends PDO {

     private $engine;
     private $host;
     private $database;
     private $user;
     private $pass;
     
     public $condition;
     //public  $wp_options;
     private $safemode_url; //change config to pull from db or external source
     public function __construct(){
         //   echo dirname(__FILE__);
         global $settings;
            

         if(!defined('DB_NAME')){
             echo 'no database parameters set!';
             exit;
         }
         $this->wp_options = array();
    
         $this->safemode_url = $settings['safemode_url'];
         $this->engine = 'mysql';
         $this->host = DB_HOST;
         $this->database = DB_NAME;
         $this->user = DB_USER;
         $this->pass = DB_PASSWORD;
         $this->condition = '';
         $dns = $this->engine.':dbname='.$this->database.";host=".$this->host;
         try{
             parent::__construct( $dns, $this->user, $this->pass );
         }catch(PDOException $ex) {

             //echo "An Error occured!"; //user friendly message
             echo '<p style="color:red">Error: </p>'. $ex->getMessage();
             //die('<p style="color:red">Error: </p>'.$ex);
             return false;
         }

     }
     
     
     function add_condition( $field, $value = '', $options = array('condition'=>'AND','operator'=>'=','equal'=> true )){
	 	if(empty($this->condition)){
			$this->condition = ' WHERE ';
		}else{
			
			$this->condition.= ' ' . $options['condition'] . ' ';
		}
		if($options['operator'] == 'LIKE' && $options['equal'] == false){
			$value = '%'. $value . '%';
		}
	    $this->condition.=  $field . $options['operator'] . "'" . $value . "'";
	//	$this->condition.= $field . ' ' . 
		
		
	 }
	 function get_operator($type = 'integer'){
	 	if($type == 'integer'){
			
		}
	 }
	 function check_type( $field ){
	 	if(isset($field)){
			
		}
	 }

 }

global $dbModel;
$dbModel = new dbModel;
$dbModel->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);