<?php



class MainController {

    public $settings;
    public $message;
    function __construct() {
        global $settings;
        $this->settings = $settings;
        
    }

    function render($template = '', $data = '' , $includes = ''){
        if(is_array($includes)){
            foreach($includes as $include){
                include_once $include;
            }
        }
        include_once  $template.'.php';
    }
    
    function redirect( $location = ''){
	 header("location: " . $location);
	 exit;	  
	}
    
    function check_directory($filename = ''){
    	//echo '<pre>'.print_r($filename,true).'</pre>';
		if(!is_array($filename)){
			if (!file_exists($filename)) {
			    mkdir($filename, 0777);
			   return;
			}			
		}else{
			foreach($filename as $dir){
			if (!file_exists($dir)) {
			    mkdir($dir, 0777);
			    //exit;
			}		
			}
		}
     return;
	}
	
	function set_message($message = ''){
		if(empty($message))
		return;
		
		$message.='<br/>';
		$this->message.= '';
		if(!isset($_SESSION['sfmessage'])){
			$_SESSION['sfmessage'] = '';
		}
		$_SESSION['sfmessage'].= $message;
	}
	
	function get_message(){
		if(isset($_SESSION['sfmessage'])){
			$message = $_SESSION['sfmessage'];
			unset($_SESSION['sfmessage']);
			return $message;
		}
	}
	

}