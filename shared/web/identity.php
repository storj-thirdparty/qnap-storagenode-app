<?php
# ------------------------------------------------------------------------
#  Set variables
# ------------------------------------------------------------------------
$platformBase   = $_SERVER['DOCUMENT_ROOT'];
$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
$scriptsBase    = $moduleBase . '/scripts' ;

$identityGenBinary = "/share/Public/identity.bin/identity" ;
$logFile = "/share/Public/identity/logs/storj_identity.log" ;


$identityGenScriptPath = $scriptsBase . DIRECTORY_SEPARATOR . 'generateIdentity.sh' ;
$Path = "/root/.local/share/storj/identity/storagenode/";
$identityFilePath = "/root/.local/share/storj/identity/storagenode/identity.key" ;
$urlToFetch = "https://github.com/storj/storj/releases/latest/download/identity_linux_amd64.zip" ;
$centralLogFile = "/var/log/STORJ" ;

# ------------------------------------------------------------------------

function validateExistence() {
	global $Path ;
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
}


function identityExists() {
    	global $identityFilePath ;
	return file_exists($identityFilePath);
}

    date_default_timezone_set('Asia/Kolkata');
    $date = Date('Y-m-d H:i:s');
    $output = "" ;
    $configFile = "config.json";

    logMessage( "================== identity.php invoked ================== ");
    if (isset($_POST["createidval"])){
		logMessage("Identity php called for creation purpose identityString : " . $_POST['identityString']);
		
		if(identityExists() && validateExistence()) {
		    logMessage("Identity Key File and others already available");
		    echo "Identity Key File and others already available";
		    return ;
		} else {
			logMessage("Identity Key doesn't exists. Going to start identity generation ");
		}

		if(!isset($_POST["identityString"]))  {
		    logMessage("Identity String not provided");
		    echo "Identity String not provided";
		    return ;
		}
		$identityString = $_POST["identityString"] ;
		logMessage("value of identityString($identityString)");

		$cmd = "$identityGenScriptPath $identityString > ${logFile}.a 2>&1 & "; 
	
		$programStartTime = Date('Y-m-d H:i:s');
		logMessage("Launching command $cmd and capturing log in $logFile ");
		exec($cmd, $output );
		logMessage("Launched command (@ $programStartTime) ");

		$jsonString = file_get_contents($configFile);
		$data = json_decode($jsonString, true);
		$data['LogFilePath'] = $logFile;
		$data['AuthKey'] = $identityString;
		$data['Identity'] = $_POST["identitypath"];
		#$data['idGenPid'] = $pid ;
		$data['idGenStartTime'] = $programStartTime ;
		$newJsonString = json_encode($data);
		$file = $data['LogFilePath'];
		$file = escapeshellarg($file);
	    $lastline = `tail -c 59 $file `;
		file_put_contents($configFile, $newJsonString);

	    logMessage("Invoked identity generation program ($identityGenScriptPath) ");
	    echo $lastline;

    } else if (isset($_POST["status"])) {
		logMessage("Identity php called for fetching STATUS!");

	    $jsonString = file_get_contents($configFile);
	    $data = json_decode($jsonString, true);
	    $file = $data['LogFilePath'];
	    // $pid =  $data['idGenPid']  ;
	    $pid = file_get_contents("identity.pid");
	    $prgStartTime = $data['idGenStartTime'] ;
	    $file = escapeshellarg($file);
	    $lastline =  `tail -c160 $file | sed -e 's#\\r#\\n#g' | tail -1 ` ;

	    if( identityExists() && validateExistence()) {
		logMessage("STATUS: Identity exists ! returning message");
		    logMessage("identity available at /root/.local/share/storj/identity");
		echo "identity available at $identityFilePath " ;
	    } else if($lastline == "Done"){	# EXACT Check to be figured out 
		    logMessage("STATUS: Identity generation completed. Returning message");
		    logMessage("identity available at /root/.local/share/storj/identity");
		    echo "identity available at /root/.local/share/storj/identity" ;
	    }else{
	    	$lastline = preg_replace('/\n$/', '', $lastline);
		logMessage("STATUS: Identity generation in progress (LOG: $lastline)");
		echo "Identity generation STATUS($date):<BR> " .
			"Process ID: $pid , " .
			    "Started at:  $prgStartTime <BR>" . $lastline ;
	    }

    } else if (isset($_POST["validateIdentity"])) {
	$val = isset($_POST["identityString"]) ? $_POST["identityString"] : "NOT SET" ;
	logMessage("<<< DEPRECATED RUN >>> Identity php called for authorizing IDENTITY (id string : $val)!");
    }else if (isset($_POST["file_exist"])) {
	logMessage("Identity php called for finding file existence");
    	// Checking file if exist or not.
    	if(validateExistence())
	{
		logMessage("(file_exist) File $identityFilePath and others already exist !");
    		echo "0";	# NORMAL
    	}else{
		logMessage("(file_exist) File $identityFilePath or others don't exists !");
    		echo "1";	# FILE NOT FOUND
    	}
    }

    else if(isset($_POST['isstopAjax']) && ($_POST['isstopAjax'] == 1)){
	    // Stop Identity
  	}

    else {
		logMessage("Identity php called (PURPOSE NOT CLEAR)!");
    }
    return (0);

function logEnvironment() {
	logMessage( "POST is : " . print_r($_POST, true));
}

function logMessage($message) {
    global $centralLogFile ;
    $file = $centralLogFile ;
    $message = preg_replace('/\n$/', '', $message);
    $date = `date` ; $timestamp = str_replace("\n", " ", $date);
    $timestamp .= " (identity.php)  "  ;
    file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
}

?>
