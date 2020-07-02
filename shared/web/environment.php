<?php

	# ------------------------------------------------------------------------
	#  Set environment variables
	# ------------------------------------------------------------------------
	$filename = "config.json";

	$platformBase   = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
	// @codingStandardsIgnoreStart
	$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
	 // @codingStandardsIgnoreEnd
	$scriptsBase    = $moduleBase . '/scripts' ;
	// $rootBase	= "/root/.local/share/storj/identity" ;


	$file           = $moduleBase  . DIRECTORY_SEPARATOR . $filename  ;
	$startScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestart.sh' ;
	$stopScript     = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestop.sh' ;
	$updateScript	= $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodeupdate.sh' ;
	$checkScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'checkStorj.sh' ;
	$isRunning      = $scriptsBase . DIRECTORY_SEPARATOR . 'isRunning.sh' ;
	$storageBinary  = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenode' ;

	$identityFile   = $moduleBase  . DIRECTORY_SEPARATOR . 'identity.pid' ;

?>