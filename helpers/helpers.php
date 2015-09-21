<?php





class DashboardHelpers {
	
	
	
	function __construct(){
		
	}
	
	public static function wp_escape($string = ''){
	//	escape
	}
	
	public static function wp_addslashes( $string = '', $is_like = false ) {

	    if ( $is_like )
	    	$string = str_replace( '\\', '\\\\\\\\', $string );

	    else
	    	$string = str_replace( '\\', '\\\\', $string );

	    $string = str_replace( '\'', '\\\'', $string );

	    return $string;
	}
	public static function str_lreplace($search, $replace, $subject)
		{
		    $pos = strrpos($subject, $search);

		    if($pos !== false)
		    {
		        $subject = substr_replace($subject, $replace, $pos, strlen($search));
		    }

		    return $subject;
		}

	public static function merge_files($files = array(),$filepath = '', $remove_files = false){
		 $out = fopen($filepath, "w");
    //Then cycle through the files reading and writing.

	      foreach($files as $file){
	          $in = fopen($file, "r");
	          while ($line = fgets($in)){
	              //  print $file;
	               fwrite($out, $line);
	          }
	          fclose($in);
	      }

	    //Then clean up
	    fclose($out);
    if($remove_files == true){
		self::remove_files($files);
	}
    return $filepath;
	}
    
    public static function remove_files($files = ''){
    	if(!empty($files) && is_array($files)){
			foreach($files as $file){
				if(file_exists($file)){
					unlink($file);
				}
			}
		}    	
		
	}
	

    
    #------------------------------------------// ZIP ALL DATA --------------------------------------------------------#
    public static function zip_all_data($source, $destination) {
        
        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);
                       if(strstr($source,'\\')){
								$source = str_replace('\\','/',$source);
							}
							//echo $source;
							//exit;
                    if (is_dir($source)) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if(strstr($file,'\\')){
								$file = str_replace('\\','/',$file);
							}
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else if (is_file($file)) {
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }
    
    public static function zip_data($files = '',  $destination , $sourcedir = ''){
		if (extension_loaded('zip')) {
			if(!empty($files)){
				 $sourcedir = rtrim($sourcedir, '/');
				 $zip = new ZipArchive();
				 if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                         
                        foreach ($files as $file) {
                          //  $file 
                            $file = realpath($file);
                            if(strstr($file,'\\')){
								$file = str_replace('\\','/',$file);
							}
                            if (is_dir($file)) {
                                //$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                               // echo $file . '<br/>';
                                $zip->addEmptyDir(str_replace($sourcedir . '/', '', $file . '/'));
                            } else if (is_file($file)) {
                            	
                            
                            	
                                //$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                $zip->addFromString(str_replace($sourcedir. '/', '', $file), file_get_contents($file));
                            }
                        }

                }
                return $zip->close();
                exit;
			}
			
		}
		return false;
	}

    #-----------------// safemode_unzip( $filename = '', $destination = '', $deletezip = false); ----------------------#
    public static function safemode_unzip( $filename = '', $destination = '', $deletezip = false){
        if(!file_exists($filename))
            return;
        $zip = new ZipArchive;
        $res = $zip->open($filename);
        if ($res === TRUE) {
            if($destination == ''){
                $destination = dirname($filename);
            }
            echo $destination;
            $zip->extractTo($destination);
            $zip->close();
            if( $deletezip == true){
                unlink($filename);
            }
          //  echo '  - theme downloaded!';
        } else {
           // echo '  error!';
        }
    }
    
    public static function download_file($filename, $filepath){
    	//$finfo = new finfo;
      //  $fileinfo = $finfo->file($file, FILEINFO_MIME);
		header('Content-type: "application/octet-stream"');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($filepath);
	}

	
}