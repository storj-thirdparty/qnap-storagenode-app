<html>
<head>
<title>Authorize</title>
</head>
<body>
<div style='font-size:30px'>
In this example, the program "authorize" is executed.</br>
<?php
    echo 'Result of helloWorld</br>';
    $output = shell_exec("/etc/init.d/STORJ.sh authorize partnerships@storj.io:1Bsg4UoBjb3Wz1NNMmHLgBAMZ5SaJBgT2C6kmKQaForB84gMaTo4R6xDzZFb6LAeuny4iQPtbSsSeFCmVDDFKUzbaTHi6o");
    #$output = shell_exec("/etc/init.d/STORJ.sh is-authorized 2>&1");
    echo "$output";
?>
</br>
</div>
</body>
</html>
