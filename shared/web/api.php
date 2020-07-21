<?php
	$arr = array ();
	//Change the variable below to set the default path
	$path = "/share/Public/";

	if(isset($_GET['action']) == null || isset($_GET['action']) == ""){
		error("Invalid or Unknown API Request"); 
	}else{
		if($_GET['action'] !=="folders"){
			error("Invalid or Unknown API Request"); 
		}else{

			$dirs = array();

			if(isset($_GET['path'])){
				$path = $_GET['path'];
			}

			if ( !is_dir( $path ) ) {
				$path = "/";
			}
                        $dir = dir($path);
                        while (false !== ($entry = $dir->read())) {
                            if ($entry != '.' && $entry != '..') {
                                if (is_dir($path . '/' . $entry)) {
                                    $dirs[] = $entry;
                                }
                            }
                        }
                        $arr = array(
                            "cd" => $path,
                            "folders" => $dirs
                        );
                    }
			
	}

	function error($msg){
		global $arr;
		$arr = array ( "error" => $msg); 
		return $arr;
	}


	echo json_encode($arr); 

	
?>