<?php include 'header.php';?>
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
      <div class="col-10">
        <div class="container-fluid">
          <?php
              $ip = "http://173.225.183.161";
              $_finalIp = $ip.":14002/".
              $output = shell_exec("/etc/init.d/STORJ.sh is-running");
              if (!trim($output) == "") { ?>
               <iframe src="<?php echo $ip; ?>" width="100%" height="100%"></iframe>
             <?php } else {
               echo $output;
              } ?>
        <div>
      </div>
    </div>
  </div>

<?php include 'footer.php';?>
