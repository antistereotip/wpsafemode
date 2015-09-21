<?php

//include db class
include_once('db.model.php');
include_once('helpers/helpers.php');
#--never include ('controller');--#

#---------------------------------------------// class extends db model -----------------------------------------------#
class DashboardModel extends dbModel {

    //construct method from parent class
    #-------------------------------------------------- __construct ---------------------------------------------------#
    public function __construct(){
        parent::__construct();

        global $settings;
        $this->settings = $settings;
        $this->wp_config = $this->get_wp_config();
        $this->wp_config_array = $this->get_wp_config_array();
        $this->default_themes = array(
            'twentyfifteen'=>'Twenty Fifteen',
            'twentyfourteen'=>'Twenty Fourteen',
            'twentythirteen'=>'Twenty Thirteen',
            'twentytwelve'=>'Twenty Twelve',
        );
        
      // $this->helpers = new DashboardHelpers;

    }





    #---------------------------------------------// Get WP Config File -----------------------------------------------#
    public function get_wp_config(){
        $wp_dir = $this->settings['wp_dir'];
        $fileStr = file_get_contents($wp_dir."wp-config.php");
        if(!file_exists($wp_dir."wp-config.php.safemode.backup")){ //do the backup of wp_config.php in case something goes wrong, like milutin erasing all data from it :P
            file_put_contents($wp_dir."wp-config.php.safemode.backup", $fileStr);
        }
        return $fileStr;
        //return file_put_contents("../benchmark-safemode/wp-config.php",$fileStr);
    }

    #---------------------------------------------// Get WP Config File In Array --------------------------------------#
    function get_wp_config_array(){
        $wp_dir = $this->settings['wp_dir'];
        $fileStr = $this->wp_config;
        $newStr  = '';
        $commentTokens = array(T_COMMENT);
        if (defined('T_DOC_COMMENT'))
            $commentTokens[] = T_DOC_COMMENT; // PHP 5
        if (defined('T_ML_COMMENT'))
            $commentTokens[] = T_ML_COMMENT;  // PHP 4
        $tokens = token_get_all($fileStr); //proveriti da li je po defaultu enabled ovaj module za php
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens))
                    continue;
                $token = $token[1];
            }
            if(!empty($token)){
                $newStr.= $token;
            }
        }
        $ini_array = explode(PHP_EOL,$newStr);
        return $ini_array;

    }

    #---------------------------------------------// SAVE WP CONFIG ---------------------------------------------------#
    function save_wpconfig($data = ''){
        $wp_dir = $this->settings['wp_dir'];
        if(!empty($data)){
            $data = trim($data);
            file_put_contents($wp_dir."wp-config.php",$data);
        }
    }







    #---------------------------------------------// Get All Info About Active Plugins --------------------------------#
    public function get_active_plugins(){
        $prefix = $this->settings['wp_db_prefix'];
        $q = $this->query("SELECT * FROM ".$prefix."options WHERE option_name LIKE 'active_plugins';");
        $q->execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    #---------------------------------------------// Get All Info About Active Plugins --------------------------------#
    public function get_plugins_to_revert(){
    	$plugins_file = $this->settings['sfstore'] . 'active_plugins.txt';
    	if(file_exists($plugins_file)){
        $file_contents = file_get_contents($plugins_file);
        if(!empty( $file_contents )){
        $this->save_plugins($file_contents);	
        return true;
		}else{
		return false;
		}
		}else{
			//echo 'file doesnt exist';
			//exit;
			return false;
		}

    }
    
    public function backup_plugins_data(){
    	 $sfstore = $this->settings['sfstore'];
    	 $file_backup = $sfstore.'active_plugins.txt';
		$active_plugins = $this->get_active_plugins();
		if($active_plugins){
			  if(file_exists($file_backup)){
			  	unlink($file_backup);
			  }
			 file_put_contents($file_backup, $active_plugins['option_value']);
		}
	}
    function plugin_info_cleanup( $info = ''){
    	//$info = addslashes($info);
		$info = strip_tags($info);
		$info = str_replace(array('\/*','*\/','"',"'"),'',$info);
		$info =  $info;
		return $info;
	}
    #----------------------------------------// Scan All WP Plugins Info ----------------------------------------------#
    function scan_wordpress_plugins($wordpress_dir = ''){
        $count = 0;
        $all_plugins_arr = array();
        foreach(glob($wordpress_dir .'wp-content/plugins/*') as $dir) {
            if(is_file($dir) && strstr($dir,'hello')){
             $plugin_dir = str_replace($wordpress_dir . 'wp-content/plugins/', '', $dir);
             $plugin_path = $plugin_dir;
             $all_plugins_arr[$plugin_path]['name'] = 'Hello Dolly';
             $filecontents = file_get_contents($dir);
             preg_match_all('/\/\*(.*)\*\//sU', $filecontents, $filecontents_arr);
             $found_plugin_info = false;
             foreach ($filecontents_arr as $filecontent) {
              foreach ($filecontent as $filecontent_data) {
                if (strstr($filecontent_data, 'Plugin Name:') && strstr($filecontent_data, 'Version:') && $found_plugin_info == false) { 
                            $file_info = $filecontent_data;
                            $all_plugins_arr[$plugin_path]['info'] = $this->plugin_info_cleanup($file_info);
                            $found_plugin_info = true;
                }
			  }								
			 }
            }
            elseif(is_dir($dir)){
                $found_plugin_info = false;
                $plugin_dir = str_replace($wordpress_dir . 'wp-content/plugins/', '', $dir);
                foreach (glob($dir . '/*.php') as $filename) {
                    if ($found_plugin_info == false) {
                        $filecontents = file_get_contents($filename);
                        if (strstr($filecontents, 'Plugin Name:') && strstr($filecontents, 'Version:')) {
                            $plugin_main_file = str_replace($wordpress_dir . 'wp-content/plugins/', '', $filename);
                            $count++;
                            preg_match_all('/\/\*(.*)\*\//sU', $filecontents, $filecontents_arr);
                            //echo '<pre>'.print_r($filecontents_arr,true).'</pre>'."<br>";
                            foreach ($filecontents_arr as $filecontent) {
                                foreach ($filecontent as $filecontent_data) {
                                    if (strstr($filecontent_data, 'Plugin Name:') && strstr($filecontent_data, 'Version:') && $found_plugin_info == false) { 
                                        $file_info = $filecontent_data;
                                        $filecontent_data = str_replace(array("\*\/", "\/\*"), '', $filecontent_data);
                                        $filecontent_data = str_replace(array("\n", '#', '*'), PHP_EOL, $filecontent_data);
                                        $plugin_data_arr = explode(PHP_EOL, $filecontent_data);
                                        foreach ($plugin_data_arr as $plugin_data_line) {
                                            if (strstr($plugin_data_line, 'Plugin Name:')) {

                                                $plugin_name = str_replace('Plugin Name: ', '', $plugin_data_line);
                                                $plugin_name = trim($plugin_name);
                                            }
                                            //  echo $plugin_data_line . "<br/>\n";
                                        }
                                        $found_plugin_info = true;
                                    }
                                }
                            }
                        }
                    }


                }
                $plugin_path = $plugin_main_file;
                $all_plugins_arr[$plugin_path]['name'] = $plugin_name;
                $all_plugins_arr[$plugin_path]['info'] = $this->plugin_info_cleanup($file_info);
             
              //  $all_plugins_arr[$plugin_path]['name'] = $plugin_name;
            }
        }
        return $all_plugins_arr;
    }

    #----------------------------------------// SAVE PLUGINS STATE-------- --------------------------------------------#
    public function save_plugins($option_value = ''){
        $prefix = $this->settings['wp_db_prefix'];
        $q = $this->query("UPDATE ".$prefix."options SET option_value = '".$option_value."' WHERE option_name LIKE 'active_plugins';");
        $q->execute();
        //   return $q->fetchAll();
    }

    #----------------------------------------// Disable All Active Plugins --------------------------------------------#
    public function disable_all_plugins(){
        $prefix = $this->settings['wp_db_prefix'];
        $q = $this->query("UPDATE ".$prefix."options SET option_value = '' WHERE option_name = 'active_plugins';");
        $q->execute();
        //return $q->fetchAll();
    }








    #---------------------------------------------// GET ACTIVE THEMES ------------------------------------------------#
    public function get_active_themes(){
        $prefix = $this->settings['wp_db_prefix'];
        $q = $this->query("SELECT * FROM  ".$prefix."options WHERE option_name = 'template' OR option_name = 'stylesheet' OR option_name = 'current_theme';");
        $q->execute();
        return $q->fetchAll();
    }

    #-------------------------------------// SET ACTIVE THEME ---------------------------------------------------------#
    function set_active_theme($theme=''){
        if(!empty($theme)){
            // print_r($theme);
            // exit;
            $prefix = $this->settings['wp_db_prefix'];
            foreach($theme as $key=>$value){
                $q = $this->query("UPDATE  ".$prefix."options SET option_value = '".$value."' WHERE option_name = '".$key."';");
                $q->execute();
            }
           
        }
    }

    #---------------------------------------// GET ALL THEMES ---------------------------------------------------------#
    function get_all_themes($wordpress_dir = ''){
        $count = 0;
        $themes_data = array();
        foreach(glob($wordpress_dir .'wp-content/themes/*') as $dir){
            if(is_dir($dir)){
                $found_plugin_info = false;
                $theme_slug = str_replace($wordpress_dir .'wp-content/themes/','',$dir);
                $filename = $dir . '/style.css';
                $filecontents = file_get_contents($filename);
                $theme_data = array();
                $theme_main_file =  str_replace($wordpress_dir .'wp-content/themes/','',$filename);
                $count ++;
                preg_match_all('/\/\*(.*)\*\//sU', $filecontents, $filecontents_arr);
                //echo '<pre>'.print_r($filecontents_arr,true).'</pre>'."<br>";
                foreach($filecontents_arr as $filecontent){
                    foreach($filecontent as $filecontent_data){
                        $filecontent_data = str_replace(array("\*\/","\/\*"),'',$filecontent_data);
                        $filecontent_data = str_replace(array("\n",'#','*'),PHP_EOL,$filecontent_data);
                        $theme_data_arr = explode(PHP_EOL,$filecontent_data);
                        foreach($theme_data_arr as $theme_data_line){
                            if(strstr($theme_data_line, 'Theme Name:')){
                                $theme_name = str_replace('Theme Name: ','',$theme_data_line);
                                $theme_data['theme_name'] = trim($theme_name);
                            }
                            if(strstr($theme_data_line, 'Template:')){
                                $template = str_replace('Template: ','',$theme_data_line);
                                $template = trim($template);
                                if($theme_slug!=$template){
                                    $theme_data['theme_parent'] = $template;
                                }

                            }
                            //echo $theme_data_line . "<br/>\n";
                        }
                    }
                }
                $themes_data[$theme_slug] = $theme_data;
            }
        }

        return $themes_data;
    }






    #---------------------------------// safemode_download_theme('twentyfifteen'); ------------------------------------#
    public function safemode_download_theme( $theme = 'twentyfifteen' ){
        $download_url = 'http://downloads.wordpress.org/theme/';
        $wp_dir = $this->settings['wp_dir'];
        $default_themes = $this->default_themes;
        foreach($default_themes as $available_theme => $theme_name ){
            if($available_theme == $theme){
                set_time_limit(0);
                $url =  $download_url . $theme . '.zip';
                $filename = $wp_dir .'wp-content/themes/' . $theme . '.zip';
                $file = fopen($filename , 'w+');
                $curl = curl_init($url);
                // Update as of PHP 5.4 array() can be written []
                curl_setopt_array($curl, [
                    CURLOPT_URL            => $url,
                    CURLOPT_BINARYTRANSFER => 1,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FILE           => $file,
                    CURLOPT_TIMEOUT        => 50,
                    CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
                ]);
                $response = curl_exec($curl);
                if($response === false) {
                    // Update as of PHP 5.3 use of Namespaces Exception() becomes \Exception()
                    throw new \Exception('Curl error: ' . curl_error($curl));
                }
               DashboardHelpers::safemode_unzip( $filename,'', true);
                //return $destination;
                //$response; // Do something with the response.
            }
        }
    }
    public function get_database_backups(){
    	$backups = array();
    	$backups['csv'] = array();
    	$backups['tables'] = array();
    	$backups['database'] = array();
    	
    	$sourcedir_tables_csv =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/csv/';
    	$sourcedir_tables_database =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/database/';
    	$sourcedir_tables_tables =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/tables/';
		 foreach(glob($sourcedir_tables_tables.'*') as $dir) {
		 	if(!is_dir($dir)){
			$backups['tables'][] = $dir;	
			}
		 	
		 }
		 foreach(glob($sourcedir_tables_csv.'*') as $dir) {
		 	if(!is_dir($dir)){
			$backups['csv'][] = $dir;	
			}
		 }
		 foreach(glob($sourcedir_tables_database.'*') as $dir) {
		 	if(!is_dir($dir)){
			$backups['database'][] = $dir;	
			}
		 }
		 return $backups;
	}
	
	public function get_file_backups(){
        $backups = array();
    	$backups['full'] = array();
    	$backups['partial'] = array();
	  
		$sourcedir_full =  $this->settings['safemode_dir'].$this->settings['sfstore'].'file_backup/full/';
		$sourcedir_partial =  $this->settings['safemode_dir'].$this->settings['sfstore'].'file_backup/partial/';
		
		foreach(glob($sourcedir_full.'*') as $dir) {
		 	if(!is_dir($dir)){
			$backups['full'][] = $dir;	
			}
		 	
		 }
		
		foreach(glob($sourcedir_partial.'*') as $dir) {
		 	if(!is_dir($dir)){
			$backups['partial'][] = $dir;	
			}
		 	
		 }	
		 
		 return $backups;	 
	}
    #------------------------------------------------// SHOW TABLES ---------------------------------------------------#
    public function show_tables(){
        try{
            $q = $this->query("SHOW TABLES FROM " . DB_NAME . "");
            $q->execute();
        }catch(PDOException $ex) {
            echo '<p style="color:red">Error: </p>'. $ex->getMessage();
            return false;
        }
        //$backup_file  = "dbbackup/employee.sql";
        //$q = $this->query("SELECT * INTO OUTFILE '$backup_file' FROM devcloud_wp6");
        return $q->fetchAll(PDO::FETCH_COLUMN);
        //return $q->fetchAll();
    }
    
    
    public function backup_tables_csv($allowed_tables = '' , $archive = false){
		 $tables = $this->show_tables();
		  $sourcedir_tables_csv =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/csv/';
		  if(!empty($allowed_tables)){
			$tables = $this->db_allowed_tables_filter($tables, $allowed_tables);
		   }
		   $date = date('d-m-Y--H-i-s');
		    $backup_file_csv_zip = $sourcedir_tables_csv.'tables_database_'.DB_NAME.'-'.$date.'.zip';
		   $backup_files_csv = array();
		   foreach($tables as $table){
		   	   $backup_file_csv = $sourcedir_tables_csv.$table.'-'.$date.'.csv';
		       $backup_files_csv[] =  $backup_file_csv;
		       $q = $this->query("SELECT * INTO OUTFILE '". $backup_file_csv. "' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'  FROM ".DB_NAME."." . $table . "");
		   	}
		   	
		   	 if($archive == false){
			 	return $backup_files_csv;		 	
			 }else{
			 	if(DashboardHelpers::zip_data($backup_files_csv,$backup_file_csv_zip,$sourcedir_tables_csv)){
		 	     foreach($backup_files_csv as $table_file){
				  unlink($table_file);	
				 }
		 	    
			    return $backup_file_csv_zip;	
			 }
	   }
	}
    #------------------------------------------------// BACKUP TABLES -------------------------------------------------#
    public function backup_tables($allowed_tables = '' , $full_backup = true, $archive = false){
        $tables = $this->show_tables();
        
        $output = '';
        $backup_files_sql = array();
        $date = date('d-m-Y--H-i-s');
        $sourcedir_master_sql =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/database/';
        $sourcedir_tables_sql =  $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/tables/';
        $backup_file_master_sql = $sourcedir_master_sql.'database_'.DB_NAME.'-'.$date.'.sql';
        $backup_file_master_zip =  $sourcedir_master_sql.'database_'.DB_NAME.'-'.$date.'.zip';
        $backup_file_tables_zip = $sourcedir_tables_sql.'tables_database_'.DB_NAME.'-'.$date.'.zip';
        
        if(!empty($allowed_tables)){
			$tables = $this->db_allowed_tables_filter($tables, $allowed_tables);
		}
        foreach($tables as $table){
        	
				
        	$create_table = $this->db_build_create_table($table);
        	//echo '<pre>'.$create_table.'</pre>';
            $backup_file = $sourcedir_tables_sql.'table_'.$table.'-'.$date.'.sql';
           // $backup_file_zip = $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/tables/table_'.$table.'-'.$date.'.zip';
            $backup_files_sql[] = $backup_file;
            $backup_file_csv = $this->settings['safemode_dir'].$this->settings['sfstore'].'db_backup/csv/'.$table.'-'.$date.'.csv';
            if(file_exists($backup_file)){
				unlink($backup_file);
			}
			if(file_exists($backup_file_csv)){
				unlink($backup_file_csv);
			}
			
			$table_records = $this->db_build_insert_records($table);
			$content = $create_table.$table_records;
			//$content = $create_table;
			file_put_contents($backup_file,$content);
			
			//file_put_
			//echo '<pre>'.$table_records.'</pre>';
            // file_put_contents($backup_file,'');
           // echo $backup_file;
            try{
            //   echo "SELECT * INTO OUTFILE '". $backup_file . "' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\' FROM ".DB_NAME."." . $table . "<br/>";
             //  $q = $this->query("SELECT * INTO OUTFILE '". $backup_file . "' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '),\n('  FROM ".DB_NAME."." . $table . "");
             
             
                // $q->execute();
                //$dummy = $q->fetchAll();
                // print_r($dummy);
            }catch(PDOException $ex) {
                echo '<p style="color:red">Error: </p>'. $ex->getMessage();
                return false;
            }
            
        }
        if($full_backup == true){
		 $full_path = DashboardHelpers::merge_files($backup_files_sql,$backup_file_master_sql,true);	
		 if($archive == false){
		 	return $full_path;
		 }else{
		 	if(DashboardHelpers::zip_data(array($full_path),$backup_file_master_zip, $sourcedir_master_sql)){
		 	unlink($full_path);
			return $backup_file_master_zip;	
			}
		 	
		 }
		}else{
			 if($archive == false){
			 	return $backup_files_sql;		 	
			 }else{
			 	if(DashboardHelpers::zip_data($backup_files_sql,$backup_file_tables_zip,$sourcedir_tables_sql)){
		 	     foreach($backup_files_sql as $table_file){
				  unlink($table_file);	
				 }
		 	    
			    return $backup_file_tables_zip;	
			 }
		//	$backup_file_tables_zip
		}
            
       }
    }
    function db_build_insert_records( $table = '' ){
		if(empty($table)){
			return;
		}
		$search   = array( '\x00', '\x0a', '\x0d', '\x1a' ); 
	    $replace  = array( '\0', '\n', '\r', '\Z' );
		$q = $this->query( 'SELECT * FROM ' . $table );
		$q->execute();
		$output = '';
		$output.= '--' . PHP_EOL . '-- Dumping data for table '. $table . PHP_EOL . '--' . PHP_EOL;
		$output.= 'INSERT INTO '.$table.' VALUES '. PHP_EOL;
		$rows_output = '';
		while( $row = $q->fetch(PDO::FETCH_ASSOC)){
		  $num_fields = count($row);
		  $j=0;
		  $rows_output.= "(";
		  foreach($row as $field){
		  		  //  $field = addslashes($field);
				$field = str_replace("\n","\\n",$field);
				$field = str_replace("\r","\\r",$field);
				$field = str_replace("'","''",$field);
				//$rows_output.="'" . str_replace( $search, $replace, DashboardHelpers::wp_addslashes( $field ) ) . "'";
				$rows_output.= $this->quote($field);
				//	$rows_output.= "'".$field."'" ; 
						
		  if ($j<($num_fields-1)) { 
			$rows_output.= ','; 
		  }
		  	$j++;
		  }
           $rows_output.= ")," . PHP_EOL;
		  
		}
		if(!empty($rows_output)){
			$rows_output = stripslashes($rows_output);
		$output.= DashboardHelpers::str_lreplace(',',';',$rows_output);	
		$output.= "\n\n\n" . PHP_EOL;
		return $output;
		}
		
		//$output.= ");\n";
		
	}
    function db_show_columns( $table = ''){
		if(!empty($table)){
			
			$q = $this->prepare("SHOW FULL COLUMNS FROM ".$table);
			$q->execute();
			return $q->fetchAll(PDO::FETCH_ASSOC);
			
		}
		
	}
	function db_show_keys( $table = ''){
		    $q = $this->prepare("SHOW KEYS FROM ".$table);
			$q->execute();
			return $q->fetchAll(PDO::FETCH_ASSOC);
		
	}
	function db_show_table_info( $table = '' ){
		  $q = $this->prepare("SHOW TABLE STATUS FROM " . DB_NAME . " WHERE Name = '" . $table . "'");
		  $q->execute();
		  return $q->fetch( PDO::FETCH_ASSOC );
		//SHOW TABLE STATUS WHERE Name = 'xxx'
	}
    function db_build_create_table( $table = ''){
    	//$table_primary = 
    //	echo 'table ' . $table . '<br/>';
			$table_keys = $this->db_show_keys( $table );
			$table_columns = $this->db_show_columns( $table );
		    $table_info = $this->db_show_table_info( $table );
		    $charset = explode('_',$table_info['Collation']);
		    $table_info['Charset'] = $charset[0];
	    //echo 'info <pre>'.print_r($table_info,true).'</pre>';
	//echo 'keys <pre>'.print_r($table_keys,true).'</pre>';
		//echo 'columns <pre>'.print_r($table_columns,true).'</pre>';
		
		$output = '';
		$output.= '--' . PHP_EOL . '-- Table structure for table '. $table . PHP_EOL . '--' . PHP_EOL;
        $output.= 'CREATE TABLE IF NOT EXISTS ' . $table . ' (' . PHP_EOL;
        
        $count = 0;
        $columns_count = count($table_columns);
        //$primary_field = '';
		foreach($table_columns as $column){
		if($column['Key'] == 'PRI'){
		 $primary_field = $column;	
		}
	
	    $count++;
		$column_output = "";
		$column_output.= $column['Field'] . " " . $column['Type'];
		if(!empty($column['Collation'])){
					$column_output.= " COLLATE " . $column['Collation'];
		}
		if(!empty($column['Null']) && $column['Null'] == 'NO'){
					$column_output.= " NOT NULL ";
		}
		if($column['Null'] == 'YES' && empty($column['Default']) && strstr($column['Type'],'varchar')){
			$column_output.= " DEFAULT NULL";
		}elseif($column['Key']!='PRI'){
			$column_output.= " DEFAULT '".$column['Default']."'";
		}
		//if()
		if($count < $columns_count){
		 $column_output.= ',' ;	
		}
		$column_output.= PHP_EOL;

        $output.= $column_output;

		}
          $output.= ')';        
		  $output.= ' ENGINE=' . $table_info['Engine'];	
		  $output.= ' DEFAULT ';	
		  $output.= ' CHARSET=' . $table_info['Charset'];	
		  $output.= ' COLLATE=' . $table_info['Collation'];	
		  $output.= ';' . PHP_EOL . PHP_EOL;

		  $keys_output = '--' . PHP_EOL . '-- Indexes for table '. $table . PHP_EOL . '--' . PHP_EOL;
	      $keys_output.= 'ALTER TABLE '. $table . PHP_EOL;
	      $count = 0;
	      $keys_count = count($table_keys);
	        //look for unique joined keys 
	          $unique = array();
	          $primary = array();
	          $regular = array();
	         foreach($table_keys as $key=>$table_key){
	         		if($table_key['Key_name'] != $table_key['Column_name'] && $table_key['Key_name'] != 'PRIMARY'){
	         			if(!isset($unique[$table_key['Key_name']])){
							$unique[$table_key['Key_name']] = 0;
						}
	         		$unique[$table_key['Key_name']]+=1;	         			
	         		}
	         		if($table_key['Key_name'] == 'PRIMARY'){
	         			if(!isset($primary[$table_key['Key_name']])){
							$primary[$table_key['Key_name']] = 0;
						}
	         		   $primary[$table_key['Key_name']]+=1;	  	         			
	         			
	         		}
	         	
	         }
	         //echo '<pre>'.print_r($unique,true).'</pre>';
	        foreach($table_keys as $key=>$table_key){
	        	$count++;
	        	$sub_part = ($table_key['Sub_part'])?'('.$table_key['Sub_part'].')':'';
				if($table_key['Key_name'] == 'PRIMARY'){
					if($table_key['Seq_in_index'] == 1){
					$keys_output.= "\t".'ADD PRIMARY KEY (' .  $table_key['Column_name'] . $sub_part;
					}else{
					 $keys_output.= $table_key['Column_name'] . $sub_part;
					}
					if($table_key['Seq_in_index'] == $primary[$table_key['Key_name']]){
					 if($count < $keys_count){
					  $keys_output.= '),'. PHP_EOL; 	
					  }else{
					  $keys_output.= ');'. PHP_EOL; 	
					  }
					}else{
					$keys_output.= ',';	
					}
				}
				if($table_key['Key_name'] == $table_key['Column_name']){
					if($table_key['Non_unique'] == 1){
				 $keys_output.= "\t".'ADD KEY '.$table_key['Key_name'].' ('.$table_key['Column_name']. $sub_part .')' ;	
					}else{
				 $keys_output.= "\t".'ADD UNIQUE KEY '.$table_key['Key_name'].' ('.$table_key['Column_name']. $sub_part .')';	
					}
				}
				//check for combined unique 
				if($table_key['Key_name'] != $table_key['Column_name'] && $table_key['Key_name'] != 'PRIMARY'){
					if($table_key['Seq_in_index'] == 1){
				      if($table_key['Non_unique'] == 1){
				      $keys_output.= "\t".'ADD KEY '.$table_key['Key_name'].' ('.$table_key['Column_name']. $sub_part .'';	
				      	if($table_key['Seq_in_index'] != $unique[$table_key['Key_name']]){
						$keys_output.=',';	
						}
				      	if($table_key['Seq_in_index'] == $unique[$table_key['Key_name']]){
						if($count < $keys_count){
						 $keys_output.=  '),' . PHP_EOL; 	
						 }else{
						  $keys_output.=  ');' . PHP_EOL; 		
						 }
						}
					  }else{
				       $keys_output.= "\t".'ADD UNIQUE KEY '.$table_key['Key_name'].' ('.$table_key['Column_name']. $sub_part .',';	
					  }
					}else{
						if($table_key['Seq_in_index'] == $unique[$table_key['Key_name']]){
						if($count < $keys_count){
						 $keys_output.= $table_key['Column_name']. $sub_part . '),' . PHP_EOL; 	
						 }else{
						  $keys_output.= $table_key['Column_name']. $sub_part . ');' . PHP_EOL; 		
						 }
						}else{
						$keys_output.= $table_key['Column_name']. $sub_part . ','; 		
						}
					}
					
				}
			if( !isset($unique[$table_key['Key_name']]) && $table_key['Key_name'] != 'PRIMARY'){
			if($count < $keys_count){
					 $keys_output.= ',' ;	
				}else{
					$keys_output.= ';' ;	
				}
			
				$keys_output.= PHP_EOL;				
			}

			 }
        	
		  $output.= $keys_output;
          if(isset($primary_field) && strstr($primary_field['Extra'],'auto_increment')){
		  $autoincrement_output = '--' . PHP_EOL . '-- AUTO_INCREMENT for table '. $table . PHP_EOL . '--' . PHP_EOL;
	      $autoincrement_output.= 'ALTER TABLE '. $table . PHP_EOL;		  	
	      $autoincrement_output.= "\t".' MODIFY '.$primary_field['Field'].' '.$primary_field['Type'].' NOT NULL AUTO_INCREMENT;' . PHP_EOL;	
	      $output.= $autoincrement_output;	 
	      unset($primary_field); 	
		  }

		  return $output;
	}
	    function mysqldump(){
		
	}
	
	
	function db_allowed_tables_filter($tables = '', $allowed_tables = ''){
		if(empty($allowed_tables)){
			return $tables;
		}else{
			$new_tables = array();
			foreach($tables as $table){
				if(in_array($table,$allowed_tables)){
					$new_tables[] = $table;
				}
			}
			return $new_tables;
		}
	}


   function db_search( $term = '' , $args = array()){
   	if(empty($term))
   	return; 
   	if(isset($args['criteria']['tables']) && !empty($args['criteria']['tables']) && $args['criteria']['db'] == 'partial'){
	$tables = $this->db_allowed_tables_filter($tables, $args['criteria']['tables']);
	}
   	$tables = $this->show_tables();
   	
   	 foreach($tables as $table){
	   		$table_columns = $this->db_show_columns( $table );
	   		
	   		
	   		foreach($table_columns as $column){
				
			}
	   		//Field
	   		//echo $table . '<pre>'.print_r($table_columns,true).'</pre>';
	   		
	 }
   	
   }


#---END CLASS--#
}
