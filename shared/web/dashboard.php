<html>
<head>
<title>STORJ</title>
</head>
<body>
<div style='font-size:30px'>
Dashboard</br>
<br><br>
<?php
    if(isset($_POST['start'])) {
      $email = $_POST["email"];
      $bandwidth = $_POST["bandwidth"];
      $storage = $_POST["storage"];
      $wallet = $_POST["wallet"];
      shell_exec("/etc/init.d/STORJ.sh start-docker $wallet $email $bandwidth  $storage $port");
      #shell_exec("/etc/init.d/STORJ.sh start-docker");
     }
    if(isset($_POST['stop'])) {
      shell_exec("/etc/init.d/STORJ.sh stop-docker");
    }
    $output = shell_exec("/etc/init.d/STORJ.sh is-running");
    if ( ! trim($output) == "" ) : ?>
    <form method="post"> 
        <input type="submit" name="stop"
                value="STOP"/> 
    </form>
    <iframe src="http://68.55.169.100:14002/" width="100%" height="100%"></iframe> 
<?php else : ?>
    <form method="post">
     Wallet : <input type="text" name="wallet">
    <br><br>
     Email : <input type="text" name="email">
    <br><br>
     Bandwidth (in TBs) : <input type="number" min="1"  name="bandwidth">
    <br><br>
     Storage (in GBs) : <input type="number" min="1000" name="storage">
    <br><br>
    Port : <input type="text" value="28967" name="port"> 
    <br><br> 
    <input type="submit" name="start"
                value="START"/>
    <br><br>
    </form>
<?php endif; ?>
</br>
</div>
</body>
</html>
