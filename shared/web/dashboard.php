<?php 
	// @codingStandardsIgnoreStart
	include 'header.php'
	 // @codingStandardsIgnoreEnd
;?>
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php 
	      // @codingStandardsIgnoreStart
	      include 'menu.php'; 
	      // @codingStandardsIgnoreEnd
      ?>
      <div class="col-10">
        <div class="container-fluid dashboard-view">
	<?php
		$platformBase   = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
		// @codingStandardsIgnoreStart
		$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
		 // @codingStandardsIgnoreEnd
		$scriptsBase    = $moduleBase . '/scripts' ;
		$checkRunning	= $scriptsBase . '/checkStorj.sh' ;

              $output = shell_exec("/bin/bash $checkRunning");
              $err = shell_exec("/bin/bash $checkRunning 2>&1 ");
	      if (!trim($output) == "") { 
	      	// @codingStandardsIgnoreStart
		      echo "<H2> Storj Status </H2> " ;
		      echo " $output <br> " ;
		       // @codingStandardsIgnoreEnd
		    // @codingStandardsIgnoreStart
			$port = ":14002" ;
			$url =  "http://{$_SERVER['SERVER_NAME']}${port}";
			$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
			$_finalUrl = $escaped_url ;
			 // @codingStandardsIgnoreEnd
	?>
		<!-- @codingStandardsIgnoreStart -->
		      <a href="<?php 
		      // @codingStandardsIgnoreStart
		      echo $_finalUrl;
		      // @codingStandardsIgnoreEnd
		      ?>" target="_blank">Storj storagenode Stats </a>
		   <!--  @codingStandardsIgnoreEnd -->
       <?php 
	      }  else {
	?>
		<!-- @codingStandardsIgnoreStart -->
		<frame width="40%" height="20%">  <H2> STORJ Status is: </H2> <?php
		// @codingStandardsIgnoreStart
		 echo $err ;
		 // @codingStandardsIgnoreEnd
		 ?> </frame>
		<!--  @codingStandardsIgnoreEnd -->
	<?php
	      }
	?>

        <div>
      </div>
    </div>
  </div>

<?php 
	// @codingStandardsIgnoreStart
	include 'footer.php';
	 // @codingStandardsIgnoreEnd
?>
