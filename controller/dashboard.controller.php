<?php


include_once('settings.php');
#------------------call dashboard helpers --------------------#
include_once('helpers/helpers.php');


#------------------call dashboard model--------------------#
include_once ('model/dashboard.model.php');

#-----------call main controller---------------------------#
include_once ('main.controller.php');





#-------------------//dashboard class-----------------------#
class DashboardController extends MainController {
    
    protected $current_page;
    protected $dirs;
    
    #---------------------------------------------------- __construct()------------------------------------------------#
    function __construct(){
        parent::__construct();
        
        global $settings;
        $this->settings = $settings;
        //todo - create better array structure with nested directories in parents as keys 
        $this->dirs = array(
                $this->settings['sfstore'],
                $this->settings['sfstore'].'/db_backup',
                $this->settings['sfstore'].'/db_backup/csv',
                $this->settings['sfstore'].'/db_backup/database',
                $this->settings['sfstore'].'/db_backup/tables',
                $this->settings['sfstore'].'/file_backup',
                $this->settings['sfstore'].'/file_backup/full',
                $this->settings['sfstore'].'/file_backup/partial',       
        );        
        $this->check_directory($this->dirs);       
        $this->dashboard_model = new DashboardModel;
        $this->data['result'] = array();
        $this->data['script_url'] = $this->get_script_url();
        $this->current_page = filter_input(INPUT_GET,'view');
        $this->data['current_page'] = $this->current_page;
        $this->view_url = $this->dashboard_model->settings['view_url'];
        $this->submit();
        $this->view();

    }



    function get_script_url(){
		$script_url = $_SERVER['REQUEST_URI'];
		return $script_url;
	}
    function get_powerbar_data(){
		$powerbar_data = array();
	}
    #------------------// SUBMIT PLUGINS, THEMES, BACKUP, CONFIG ------------------------------------------------------#
    function submit(){

        #--validate--#

        #----------plugins-----------#
        $submit_plugins = filter_input(INPUT_POST,'submit_plugins');
        // <input type="submit" name="submit_plugins" value="Save Plugins Data" />
        // if()
        if(!empty($submit_plugins)){
            $this->submit_plugins();
        }

        #--------themes--------------#
        $submit_themes = filter_input(INPUT_POST,'submit_themes');
        // <input type="submit" name="submit_plugins" value="Save Plugins Data" />
        // if()
        if(!empty($submit_themes)){
            $this->submit_themes();
        }

        $submit_backup_database = filter_input(INPUT_POST, 'submit_backup_database');
         if(!empty($submit_backup_database) && $submit_backup_database = 'Backup Database Data'){
            $this->submit_backup_database();
        }       
        #-------backup---------------#
        $submit_backup = filter_input(INPUT_POST,'submit_backup_files');
        if(!empty($submit_backup) && $submit_backup == 'Backup Files'){
            $this->submit_backup_files();
        }
        $submit_search_replace = filter_input(INPUT_POST,'submit_search_replace');
        if(!empty($submit_search_replace)){
            $this->submit_search_replace();
        }
    
        #---------saveconfig---------#
        $saveconfig = filter_input(INPUT_POST,'saveconfig');

        if(!empty($saveconfig)){
            $this->submit_wpconfig();
        }

        #--validate--#

    }

    #-----------------------------// VIEW -----------------------------------------------------------------------------#
    function view(){
        	
        $this->data['message'] = $this->get_message();
       // $this->current_page = filter_input(INPUT_GET,'view');
        //$this->data['current_page'] = $this->current_page;
        #--header--#
        $this->download_backup();
        $this->render($this->view_url . 'header' , $this->data);
         
        #--validate--#
        if($this->current_page == 'plugins'){
            $this->view_plugins();
        }
        if($this->current_page == 'themes'){
            $this->view_themes();
        }
        if($this->current_page == 'wpconfig'){
            $this->view_wpconfig();
        }
        if($this->current_page == 'backup_database'){
            $this->view_backup_database();
        }
        if($this->current_page == 'backup_files'){
            $this->view_backup_files();
        }
         if($this->current_page == 'search_replace'){
            $this->view_search_replace();
        }
        //if($this->current_page == 'mybackup'){
        //    $this->backup_database();
           
        //}


        #--footer--#
        $this->render($this->view_url .'footer' , $this->data);
    }



   


    #----------------------------------------------//view plugins------------------------------------------------------#
    function view_plugins(){
        $wp_dir = $this->dashboard_model->settings['wp_dir'];
        $sfstore = $this->settings['sfstore'];
        $this->data['plugins']['active_plugins'] = $this->dashboard_model->get_active_plugins();
        $this->data['plugins']['all_plugins'] =  $this->dashboard_model->scan_wordpress_plugins($wp_dir);

        if (!file_exists($sfstore.'active_plugins.txt')) {
           $this->dashboard_model->backup_plugins_data();
        }

        $this->data['plugins']['active_plugins'] = unserialize($this->data['plugins']['active_plugins']['option_value']);
        $this->render( $this->view_url.'plugins', $this->data );
    }
    
    #--------------------------------------------------// SUBMIT PLUGINS-----------------------------------------------#
    function submit_plugins(){
        $rebuild_plugins_backup = filter_input(INPUT_POST,'rebuild_plugins_backup');
        $submit_plugins_action = filter_input(INPUT_POST,'submit_plugins_action');
        //$backup_database = filter_input(POST,'backup_database');
        if(!empty($rebuild_plugins_backup) && $rebuild_plugins_backup == 'rebuild'){
			 $this->dashboard_model->backup_plugins_data();
			 $this->set_message('Plugins backup file has been rebuild');
		}
       if(!empty($submit_plugins_action) && $submit_plugins_action == 'enable_all'){
           // $this->enable_all_plugins();
            $this->enable_selected_plugins();

        }
        if(!empty($submit_plugins_action) && $submit_plugins_action == 'disable_all'){
           // $this->disable_all_plugins();
            $this->enable_selected_plugins();
             
        }
        if(!empty($submit_plugins_action) && $submit_plugins_action == 'enable_selected'){
            $this->enable_selected_plugins();

        }

        if($submit_plugins_action == 'revert'){
            $this->revert_plugins();
        }
        
        
    }
    
    #----------------------------------------------ENABLE ALL PLUGINS--------------------------------------------------#
    function enable_all_plugins(){
    $this->redirect('?view='.$this->current_page);
    }

    #---------------------------------------------DISABLE ALL PLUGINS--------------------------------------------------#
    function disable_all_plugins(){
        $this->dashboard_model->disable_all_plugins();
        $this->redirect('?view='.$this->current_page);
    }

    #-----------------------------------------ENABLE SELECTED PLUGINS--------------------------------------------------#
    function enable_selected_plugins(){
        $selected_plugins = filter_input(INPUT_POST,'plugins',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
       // echo '<pre>'.print_r($selected_plugins,true).'</pre>';
        $selected_plugins = serialize($selected_plugins);
        $this->dashboard_model->save_plugins($selected_plugins);
        $this->set_message('Selected plugins have been enabled');
        $this->redirect('?view='.$this->current_page);
        
    }

    #------------------------------------------------------------------------------------------------------------------#
       function revert_plugins(){
        //$selected_plugins = filter_input(INPUT_POST,'plugins');
        
        $revert = $this->dashboard_model->get_plugins_to_revert();
       
        if($revert){
        	 $this->set_message('Plugins reverted to initial state');
			 $this->redirect('?view='.$this->current_page);
		}
       
      
      //  print_r($revert);
        //$this->dashboard_model->save_plugins($selected_plugins);
    }










    #----------------------------------------------//view plugins------------------------------------------------------#
    function view_themes(){

        //$view_url = $this->dashboard_model->settings['view_url'];
        $wp_dir = $this->dashboard_model->settings['wp_dir'];
        $sfstore = $this->dashboard_model->settings['sfstore'];
        $this->data['themes']['active_theme'] = $this->dashboard_model->get_active_themes();
        $this->data['themes']['all_themes'] =  $this->dashboard_model->get_all_themes($wp_dir);

        //  var_dump($this->data['themes']['all_themes']);

        //$this->data['themes']['active_themes'] = unserialize($this->data['themes']['active_themes'][0]['option_value']);
        $this->render( $this->view_url.'themes', $this->data );
    }

    #---------------------------------------------// SUBMIT THEMES ----------------------------------------------------#
    function submit_themes(){
       $set_active_theme = filter_input(INPUT_POST, 'active_theme');
       $wp_dir = $this->dashboard_model->settings['wp_dir'];
       $all_themes =  $this->dashboard_model->get_all_themes($wp_dir);
       if(!empty($set_active_theme) && $set_active_theme == 'downloadsafe'){
            $this->dashboard_model->safemode_download_theme();
            $theme = array(
                'template'=> 'twentyfifteen',
                'stylesheet'=> 'twentyfifteen',
                'current_theme'=> 'twentyfifteen',
            );
            $this->dashboard_model->set_active_theme($theme);
               $this->redirect('?view='.$this->current_page);
            return;
       }
       foreach($all_themes as $key=>$value){
           if($set_active_theme == $key){
               //option_name = 'template' OR option_name = 'stylesheet' OR option_name = 'current_theme'
               if(isset($value['theme_parent'])){
                   $theme = array(
                       'template'=> $value['theme_parent'],
                       'stylesheet'=> $key,
                       'current_theme'=> $value['theme_name'],
                   );
               }else{
                   $theme = array(
                    'template'=> $key,
                    'stylesheet'=> $key,
                    'current_theme'=> $value['theme_name'],
                   );
               }
               $this->dashboard_model->set_active_theme($theme);
               $this->redirect('?view='.$this->current_page);
               return;
           }
       }
    }


    #----------------------------------------------//view wp config----------------------------------------------------#
    function view_wpconfig(){
        $wp_dir = $this->dashboard_model->settings['wp_dir'];
        $this->data['wpconfig']['config'] = $this->dashboard_model->get_wp_config();
        $this->data['wpconfig']['array'] = $this->dashboard_model->get_wp_config_array();
        $this->render( $this->view_url .'wpconfig', $this->data );
    }

    #----------------------------------------//submit function check if id is empty or not-----------------------------#
    function submit_wpconfig(){
        $saveconfig = filter_input(INPUT_POST,'saveconfig');
        $wpdebug = filter_input(INPUT_POST,'wpdebug');
        $automatic_updater = filter_input(INPUT_POST,'automatic_updater');
        $automatic_updater_core = filter_input(INPUT_POST,'automatic_updater_core');
        //echo 'saved config';
        //exit;
        if(!empty($saveconfig)){
            $fileStr = $this->dashboard_model->get_wp_config();
            $ini_array = $this->dashboard_model->get_wp_config_array();
            //wp debug on/off
            $found_line = false;
            foreach($ini_array as $key=>$value){
                if(!empty($value)){
                    //echo $value." - from debug\n";
                    if(stristr($value,"WP_DEBUG")){
                        //echo 'foudn it';
                        if(!empty($wpdebug) && $wpdebug == 'on'){
                            $new_value = str_replace("false","true",$value);
                        }else{
                            $new_value = str_replace("true","false",$value);
                        }

                        $fileStr  = str_replace($value,$new_value,$fileStr);
                        $found_line = true;
                    }
                }
            }
            if($found_line == false){
                if(!empty($wpdebug) && $wpdebug == 'on'){
                    $add_line = "\n\n"."define('WP_DEBUG', true);\n\n";
                    $fileStr  = str_replace("/* That's all, stop editing! Happy blogging. */",$add_line."/* That's all, stop editing! Happy blogging. */",$fileStr);
                    //$fileStr.= $add_line;
                }
            }
            //automatic updater off/on
            $found_line = false;
            foreach($ini_array as $key=>$value){
                if(!empty($value)){
                    //echo $value." - from debug\n";
                    if(stristr($value,"AUTOMATIC_UPDATER_DISABLED")){
                        //echo 'foudn it';
                        $new_value = $value;
                        if(!empty($automatic_updater) || $automatic_updater == 'on'){
                            $new_value = str_replace("false","true",$value);

                        }else{
                            $new_value = str_replace("true","false",$value);
                        }
                        $fileStr  = str_replace($value,$new_value,$fileStr);
                        $found_line = true;
                    }
                }
            }
            if($found_line == false){
                $add_line = '';
                if(!empty($automatic_updater) || $automatic_updater == 'on'){
                    $add_line = "\n\n"."define('AUTOMATIC_UPDATER_DISABLED', true);\n";
                    $fileStr  = str_replace("/* That's all, stop editing! Happy blogging. */",$add_line."/* That's all, stop editing! Happy blogging. */",$fileStr);
                    //$fileStr.= $add_line;
                }

            }
            //wp autoupdate core on/off
            $found_line = false;
            foreach($ini_array as $key=>$value){
                if(!empty($value)){
                    //echo $value." - from debug\n";
                    $new_value = $value;
                    if(stristr($value,"WP_AUTO_UPDATE_CORE")){
                        //echo 'foudn it';
                        if(!empty($automatic_updater_core) && $automatic_updater_core == 'on'){
                            $new_value = str_replace("false","true",$value);
                        }else{
                            $new_value = str_replace("true","false",$value);
                        }

                        $fileStr  = str_replace($value,$new_value,$fileStr);
                        $found_line = true;
                    }

                }
            }
            if($found_line == false){
                $add_line = '';
                if(!empty($automatic_updater_core) || $automatic_updater_core == 'on'){
                    $add_line = "\n\n"."define('WP_AUTO_UPDATE_CORE', true);\n";
                    $fileStr  = str_replace("/* That's all, stop editing! Happy blogging. */",$add_line."/* That's all, stop editing! Happy blogging. */",$fileStr);


                }

            }
            $found_line = false;


            //print_r($ini_array);
            $pos=strpos($fileStr, ' ?>');
            $fileStr = substr($fileStr, 0, $pos)."\r\n".substr($fileStr, $pos);
            $this->dashboard_model->save_wpconfig($fileStr);

        }
          $this->redirect('?view='.$this->current_page);
    }

    #----------------------------------------// wp config -------------------------------------------------------------#
    function wpconfig (){
        //define vars to point on action through GET method
        $action = filter_input(INPUT_GET,'action');
        $switch = filter_input(INPUT_GET,'switch');
        //dashboard GET values and if true do action with model pointed to id
        if($action=='submit' || $switch == 'switch' && !empty($switch)){
            $this->dashboard_model->save_wpconfig($switch);
            //echo 'deleted';
            header('Location: dashboard2.php?view=wpconfig');
        }
        //$wpconfig = $this->dashboard_model->get_wp_config();
        //var_dump($wpconfig);
    }

    /*function submit_wpconfig(){

    }*/


    function download_backup(){
    	 $download = filter_input(INPUT_GET,'download');
    	 $filename = filter_input(INPUT_GET,'filename');
		 if($download == 'database'){
		 	$db_backups = $this->dashboard_model->get_database_backups();
		 	foreach($db_backups as $db_backups_section){
				foreach($db_backups_section as $backupfile){
					$basename = basename($backupfile);
					if($basename==$filename){
						DashboardHelpers::download_file($filename, $backupfile);	
						exit;
					}
				
				}
			}
		  $this->set_message('That file doesn\'t exist');	
		 }
        if($download == 'sitefiles'){
		 	$db_backups = $this->dashboard_model->get_file_backups();
		 	foreach($db_backups as $db_backups_section){
				foreach($db_backups_section as $backupfile){
					$basename = basename($backupfile);
					if($basename==$filename){
						DashboardHelpers::download_file($filename, $backupfile);	
						exit;
					}
				
				}
			}
		  $this->set_message('That file doesn\'t exist');	
		 }	
		 
	}






   



    #-------------------------------------// VIEW BACKUP --------------------------------------------------------------#
    function view_backup_database() {
        //$view_url = $this->dashboard_model->settings['view_url'];
        
        $this->data['tables'] = $this->dashboard_model->show_tables();
        $this->data['backups'] = $this->dashboard_model->get_database_backups();
        $wp_dir = $this->dashboard_model->settings['wp_dir'];
        $this->render(  $this->view_url .'db_backup', $this->data );
    }
    
    function view_backup_files(){
    	 $this->data['backups'] = $this->dashboard_model->get_file_backups();
	     $this->render(  $this->view_url .'files_backup', $this->data );
	}
	
	function submit_backup_database(){
		
		
		$backup_tables_list = filter_input(INPUT_POST,'backup_tables_list',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$backup_tables_type = filter_input(INPUT_POST,'backup_tables_type',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$backup_database_type = filter_input(INPUT_POST,'backup_database_type');
		$backup_archive = filter_input(INPUT_POST,'backup_archive');
			if(!empty($backup_archive) && $backup_archive == '1'){
				$archive = true;
			}else{
				$archive = false;
			}		
		if(!empty($backup_database_type) && $backup_database_type == 'full'){

			$this->dashboard_model->backup_tables('' , true, $archive);
		}
		if(!empty($backup_database_type) && $backup_database_type == 'partial' && !empty($backup_tables_type) && in_array('sql',$backup_tables_type)){
             //echo '<pre>'.print_r($backup_tables_list,true).'</pre>';
            // exit;
			if($tables_backup_result = $this->dashboard_model->backup_tables($backup_tables_list , false, $archive)){
				$backup_tables_list_string = implode(', ',$backup_tables_list);
			  	if(is_array($tables_backup_result)){
					$tables_backup_result = implode('<br/>',$tables_backup_result);
					
					$this->set_message('Selected tables: ' . $backup_tables_list_string . ' successfully exported in following files: <br/>' . $tables_backup_result);
				}else{
					$this->set_message('Selected tables: ' . $backup_tables_list_string . ' successfully exported in following file: <br/>' . $tables_backup_result);
				}
				
			}
		}
		if(!empty($backup_database_type) && $backup_database_type == 'partial' && !empty($backup_tables_type) && in_array('csv',$backup_tables_type)){
			if($csv_backup_result = $this->dashboard_model->backup_tables_csv($backup_tables_list , $archive)){
				$backup_tables_list_string = implode(', ',$backup_tables_list);
			  	if(is_array($csv_backup_result)){
					$csv_backup_result = implode('<br/>',$csv_backup_result);
					
					$this->set_message('Selected tables: ' . $backup_tables_list_string . ' successfully exported in following files: <br/>' . $csv_backup_result);
				}else{
					$this->set_message('Selected tables: ' . $backup_tables_list_string . ' successfully exported in following file: <br/>' . $csv_backup_result);
				}
			}
			
		}
		  $this->redirect('?view='.$this->current_page);
		//print_r($backup_tables_list);
	}

    #--------------------------------------------// SUBMIT WPCONFIG ---------------------------------------------------#
    function submit_backup_files(){
       // $backup_all_files = filter_input(INPUT_POST,'backup_all_files');
        //$backup_database = filter_input(POST,'backup_database');
       // if(!empty($backup_all_files) && $submit_backup = 'backup_all_files'){
            $this->backup_all_files();

       // }
    }
    

    #---------------------------------// BACKUP ALL WORDPRESS DATA ----------------------------------------------------#
    function backup_all_files() {
        $view_url = $this->dashboard_model->settings['view_url'];
        $wp_dir = $this->settings['wp_dir'];
        $wp_base_name = basename($wp_dir);
        $sfstore = $this->settings['sfstore'];
        $date = date('d-m-Y--H-i-s');
        $file = $sfstore.'file_backup/full/filesbackup_'.$date.'.zip';
       
        if(DashboardHelpers::zip_all_data($wp_dir, $file)){
			$this->set_message('All site files successfully archived in ' . $file );
		}
		  $this->redirect('?view='.$this->current_page);
        //$this->render( $view_url.'backup_success', $this->data , array($wp_dir.'wp-config.php'));

    }


    #-------------------------------------------// BACKUP DATABASE ----------------------------------------------------#
    function backup_database(){
        //$view_url = $this->dashboard_model->settings['view_url'];
        
        $this->data['mysql']['tables'] = $this->dashboard_model->backup_tables();
     
        $this->render( $this->view_url .'mysqlbackup', $this->data);
    }
    
    

    function view_search_replace(){
    	
           $this->data['tables'] = $this->dashboard_model->show_tables();	
         //$columns = db_show_columns();
         
		
		 $this->render( $this->view_url .'search_replace', $this->data);
	}


    function submit_search_replace(){
		 $allowed_criteria_term = array('contains','exact','any');
		 $allowed_criteria_db = array('full','partial');
		 
		 $search_term = filter_input(INPUT_POST,'term');
		 $search_criteria_term = filter_input(INPUT_POST,'search_criteria_term');		 
		 $search_criteria_db = filter_input(INPUT_POST,'search_criteria_db');
		 $search_criteria_tables = filter_input(INPUT_POST,'search_tables_list',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		 
		 $args = array();
		 
		 $args['criteria']['term'] = (!empty($search_criteria_term) && in_array($search_criteria_term, $allowed_criteria_term))? $search_criteria_term : 'contains';
		 $args['criteria']['db'] = (!empty($search_criteria_db) && in_array($search_criteria_db, $allowed_criteria_db))? $search_criteria_db : 'full';
		 if(!empty($search_criteria_tables) && $search_criteria_db == 'partial'){
		 	 $args['criteria']['tables'] = $search_criteria_tables;
		 }
		  
		 
		 if(!empty($search_term)){
		   $this->data['search_results'] = $this->dashboard_model->db_search($search_term, $args );	
		 }
		 
		 
	}





#--END CLASS--#
}
global $dashboard;
//define var for class use - not global variable
$dashboard = new DashboardController;