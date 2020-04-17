<?php

# ===========================================================================
# Problems Faced and Fixed (and to be handled)
# 1) r-x permissions for folder in path /root/.local/.... /identity.key were missing
# 2) for simulator execution, Base directory for Identity file should exists (/root/.local/share/storj/identity/storagenode/ )
# ===========================================================================

# ------------------------------------------------------------------------
#  Set variables
# ------------------------------------------------------------------------
$platformBase   = $_SERVER['DOCUMENT_ROOT'];
$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
$scriptsBase    = $moduleBase . '/scripts' ;

$identityGenBinary = "/share/Public/identity.bin/identity" ;
$identityGenSimulator = "/tmp/iSimulator.php" ;
$logFile = "/share/Public/identity/logs/storj_identity.log" ;
#$logFile = "/var/log/StorJ" ;


$identityGenScriptPath = $scriptsBase . DIRECTORY_SEPARATOR . 'generateIdentity.sh' ;
$identityFilePath = "/root/.local/share/storj/identity/storagenode/identity.key" ;
$Path = "/share/Public//identity/storagenode/";
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
    // logEnvironment();
    if (isset($_POST["createidval"])){
		logMessage("Identity php called for creation purpose identityString : " . $_POST['identityString']);
		// logEnvironment();
		
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

		$simulation = 0 ;
		if($simulation ) {
		    $identityGenScriptPath =  $identityGenSimulator ;
		    $cmd = "$identityGenScriptPath $identityString > $logFile 2>&1 "; 
		} else {

		$cmd = "$identityGenScriptPath $identityString > $logFile 2>&1  "; 

		} # Extraction of Identity generation program binary

	
		# 5) Run the binary with following arguments, and
		# 	redirect STDOUT & STDERR output to the temporary LOG FILE
		#  <BinaryFileName> create storagenode > $logFile 2>&1 
		$programStartTime = Date('Y-m-d H:i:s');
		logMessage("Launching command $cmd and capturing log in $logFile ");
		#$output = shell_exec(" $cmd > $logFile 2>&1 & " );
		#$pid = exec("$cmd > $logFile 2>&1 & ", $output );
		$pid = 0 ; 
		exec("$cmd > $logFile 2>&1 & ", $output, $pid );
		logMessage("Launched command (@ $programStartTime) process id = #$pid# ");

		# 6) Store in JSON format in (config.json)
		# 	-> Path of LOG FILE with id "LogFilePath"
		# 	-> Value 0  for "LastLineRead"

		$jsonString = file_get_contents($configFile);
		$data = json_decode($jsonString, true);
		$data['LogFilePath'] = $logFile;
		$data['idGenPid'] = $pid ;
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
		// logEnvironment();

		# 7) Get Status from LOG FILE  
		#	Find Name of LOG FILE from config.json (LogFilePath)
		#	Read Last Line of LOG FILE into output variable
		#	Print / Return output variable string

	    $jsonString = file_get_contents($configFile);
	    $data = json_decode($jsonString, true);
	    $file = $data['LogFilePath'];
	    $pid =  $data['idGenPid']  ;
	    $prgStartTime = $data['idGenStartTime'] ;
	    $file = escapeshellarg($file);
	    $lastline = `tail -c 59 $file `;

	    if( identityExists() && validateExistence()) {
		logMessage("STATUS: Identity exists ! returning message");
		    logMessage("identity available at /root/.local/share/storj/identity");
		echo "identity available at /root/.local/share/storj/identity" ;
	    } else if($lastline == "Done"){	# EXACT Check to be figured out 
		    logMessage("STATUS: Identity generation completed. Returning message");
		    logMessage("identity available at /root/.local/share/storj/identity");
		    echo "identity available at /root/.local/share/storj/identity" ;
	    }else{
	    	$lastline = preg_replace('/\n$/', '', $lastline);
		logMessage("STATUS: Identity generation in progress (LOG: $lastline)");
		echo "Identity generation STATUS($date):<BR> " .
			    "Started at:  $prgStartTime <BR>" . $lastline ;
	    }

    } else if (isset($_POST["validateIdentity"])) {
	$val = isset($_POST["identityString"]) ? $_POST["identityString"] : "NOT SET" ;
	logMessage("Identity php called for authorizing IDENTITY (id string : $val)!");
	// logEnvironment();

	# POST RUN CHECK. In case IDENTITY Creation is done (status should be 100%) 
	#
	# Ensure that identity string has been set by JS for this call.
	#  Return failure in case not set
	#
	#
	# 8) Check whether following files are created in path given
	# 	(A) Path : /root/.local/share/storj/identity/storagenode
	# 	(B) Files to check
	# 		- ca.key
	# 		- ca.cert
	# 		- identity.cert
	# 		- identity.key
	# 9) Run the authorization
	# 	<IdentityBinary> authorize storagenode <email:characterstring>
	# 10) Final Checks to be done
	# 		- Check whether ca.cert and identity.cert have BEGIN pattern
	#		- count the number of BEGIN in both files
	# 11) RETURN SUCCESS if both files have >0 # of BEGIN patterns
	#
	if(!isset($_POST["identityString"]))  {
	    echo "Identity String is not set !" ;
	    exit(1);
	}
	$identityString = $_POST["identityString"] ;

	# Validate Existence
	if(!validateExistence()) {
	    echo "One or all of required files not available!" ;
	    exit(2);
	}

	$cmd = "$identityGenBinary authorize storagenode $identityString ";
	logMessage("Launching Identity ($identityGenBinary) ");
	$output = shell_exec(" $cmd 2>&1 " );
	echo $output;
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
    } else {
	logMessage("Identity php called (PURPOSE NOT CLEAR)!");
    }
    return (0);

function logEnvironment() {
	logMessage(
	    	"" 
		#"\n----------------------------------------------\n"
		#. "ENV is : " . print_r($_ENV, true)
		. "POST is : " . print_r($_POST, true)
		#. "SERVER is : " . print_r($_SERVER, true)
		#. "----------------------------------------------\n"
	);
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
