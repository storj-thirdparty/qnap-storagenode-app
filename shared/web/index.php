<?php
    $output = shell_exec("/etc/init.d/STORJ.sh is-running 2>&1");
    if ( trim($output) == "404" ){
    header("Location: hello.php");
    } else {
    header("Location: hello1.php");
    }    
    die();
?>
