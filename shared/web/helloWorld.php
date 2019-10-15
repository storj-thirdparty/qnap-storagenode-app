<html>
<head>
<title>QNAP Hello World</title>
</head>
<body>
<div style='font-size:30px'>
<a href='index.html'>Home</a></br>
In this example, the program "helloWorld" is executed.</br>
<?php
    echo 'Result of helloWorld</br>';
    //$output = shell_exec('RET=`lsattr`;echo $RET');
    //$output = phpinfo();
    //$output = shell_exec('/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker 2>&1');
    //$output = shell_exec('/share/CACHEDEV1_DATA/.qpkg/STORJ/web/script 2>&1');
    //$output = shell_exec('pwd 2>&1');
    system("/etc/init.d/STORJ.sh run-script");
    //echo $output;
?>
</br>
</div>
</body>
</html>
