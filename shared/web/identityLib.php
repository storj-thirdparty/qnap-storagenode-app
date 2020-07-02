<?php

# ------------------------------------------------------------------------
#  Set environment variables
# ------------------------------------------------------------------------
$centralLogFile = "/var/log/STORJ" ;
// $centralLogFile = "test" ;

function checkIdentityProcessRunning($identityPidFile) {
    // @codingStandardsIgnoreStart
    if(file_exists($identityPidFile)) {
	$pid = file_get_contents($identityPidFile);
	$pid = (int)$pid ;

	// Figure out whether process is running
	$status = exec("if [ -d '/proc/$pid' ] ; then (echo 1 ; exit 0) ; else (echo 0 ; exit 2 ) ; fi");

	if($status == 1) {
	    // if process is running then print true.
	    // echo true;
	    return true ;
	} else {
	    // if process is not running then print false.
	    // echo false;
	    return false ;
	}
    }else{
	// echo false;
	return false ;
    }

     // @codingStandardsIgnoreEnd
}

function killIdentityProcess($identityPidFile) {
    // @codingStandardsIgnoreStart
    if(file_exists($identityPidFile)) {
	    $pid = file_get_contents($identityPidFile);
	    $pid = (int)$pid ;
	    // Stop Identity
	    $output = shell_exec("kill -9 $pid");
	    $msg = "Identity creation stopped (no identity generated)!\n$output";
    } else {
	    $msg = "Identity creation process not found";
    }
    logMessage($msg);
    echo $msg ;

    // @codingStandardsIgnoreEnd
}

function checkIdentityFileExistence($data) {
    // Checking file if exist or not.
    $identityFilePath = $data["Identity"] . "/storagenode/identity.key" ;
     // @codingStandardsIgnoreStart
    if(validateExistence($data))
    {
	logMessage("(file_exist) File $identityFilePath and others already exist !");
	echo "0";	# NORMAL
    }else{
	logMessage("(file_exist) File $identityFilePath or others don't exists !");
	echo "1";	# FILE NOT FOUND
    }
    // @codingStandardsIgnoreEnd
}

function validateExistence($data) {
    if(!isset($data) || (!isset($data['Identity'])) ) {
	return False ;
    }
    $Path = $data["Identity"] . "/storagenode";
    return validatePathExistence($Path);
}

function validatePathExistence($Path) {
     // @codingStandardsIgnoreStart
    $fileList = [ 
	"ca.key",
	"identity.key",
	"ca.cert",
	"identity.cert"
    ];
    $allReqdFilesAvailable = 1 ;
    foreach( $fileList as $file ) {
	if(!file_exists("$Path/$file")) {
	    $allReqdFilesAvailable = 0 ;
	}
    }
    return $allReqdFilesAvailable ;
    // @codingStandardsIgnoreEnd
}


function identityExists($data) {
     // @codingStandardsIgnoreStart
    #global $identityFilePath ;
    if(!isset($data) || (!isset($data['Identity'])) ) {
	return False ;
    }
    $Path = $data["Identity"] . "/storagenode";
    $identityFilePath = "${Path}/identity.key" ;
    return file_exists($identityFilePath);
    // @codingStandardsIgnoreEnd
}

function logEnvironment() {
      // @codingStandardsIgnoreStart
	logMessage( "POST is : " . print_r($_POST, true));
     // @codingStandardsIgnoreEnd
}

function logMessage($message) {
    // @codingStandardsIgnoreStart
    global $centralLogFile ;
    $message = preg_replace('/\n$/', '', $message);
    $date = `date` ; $timestamp = str_replace("\n", " ", $date);
    $timestamp .= " (identityLib)  "  ;
    file_put_contents($centralLogFile, $timestamp . $message . "\n", FILE_APPEND);
    // @codingStandardsIgnoreEnd
}

function loadConfig($filePath) {
     // @codingStandardsIgnoreStart
    return json_decode(file_get_contents($filePath), TRUE);
     // @codingStandardsIgnoreEnd
} 

function storeConfig($data, $filePath) {
    // @codingStandardsIgnoreStart
    return file_put_contents($filePath, json_encode($data));
    // @codingStandardsIgnoreEnd
} 

function updateConfig($dataNew, $filePath) {
    $data = loadConfig($filePath);
    $data = array_merge($data, $dataNew);
    storeConfig($data, $filePath);
}

function updateConfigKey($key, $value, $filePath) {
    $data = loadConfig($filePath);
    $data[$key] = $value ;
    storeConfig($data, $filePath);
}

?>
