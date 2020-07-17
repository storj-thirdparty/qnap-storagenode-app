<?php

	# ------------------------------------------------------------------------
	#  Set environment variables
	# ------------------------------------------------------------------------
	$filename = "config.json";

	$platformBase   = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
	$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
	$scriptsBase    = $moduleBase . '/scripts' ;
	$configBase		= '/share/Public/storagenode.conf';


	$file           = $configBase  . DIRECTORY_SEPARATOR . $filename  ;
	$startScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestart.sh' ;
	$stopScript     = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestop.sh' ;
	$updateScript	= $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodeupdate.sh' ;
	$checkScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'checkStorj.sh' ;
	$isRunning      = $scriptsBase . DIRECTORY_SEPARATOR . 'isRunning.sh' ;

	$identityFile   = $moduleBase  . DIRECTORY_SEPARATOR . 'identity.pid' ;

?>