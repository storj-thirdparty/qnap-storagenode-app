<html>
<head>
<title>QNAP Hello World</title>
</head>
<body>
<div style='font-size:30px'>
In this example, the program "helloWorld" is executed.</br>
<?php
    echo 'Result of helloWorld</br>';
    $output = shell_exec("/etc/init.d/STORJ.sh is-running");
    echo "$output";
?>
</br>
</div>
</body>
</html>
