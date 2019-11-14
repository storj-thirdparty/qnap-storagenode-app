<?php include 'header.php';?>
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
      <div class="col-10">
        <div class="container-fluid">
          <?php   $output = shell_exec("/etc/init.d/STORJ.sh is-running");
             if (!trim($output) == "") { ?>
               <iframe src="http://68.55.169.100:14002/" width="100%" height="100%"></iframe>
             <?php } else {
               echo $output;
             } ?>
        <div>
      </div>
    </div>
  </div>

<?php include 'footer.php';?>
