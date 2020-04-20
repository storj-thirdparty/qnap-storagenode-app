<?php

# ------------------------------------------------------------------------
#  Set environment variables
# ------------------------------------------------------------------------
$filename = "gatewayconfig.json";

$platformBase   = $_SERVER['DOCUMENT_ROOT'];
$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
$scriptsBase    = $moduleBase . '/scripts' ;
$rootBase = "/root/.local/share/storj/identity" ;


$file           = $moduleBase  . DIRECTORY_SEPARATOR . $filename  ;
$startScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestart.sh' ;
$stopScript     = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodestop.sh' ;
$updateScript = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenodeupdate.sh' ;
$checkScript    = $scriptsBase . DIRECTORY_SEPARATOR . 'checkStorj.sh' ;
$isRunning      = $scriptsBase . DIRECTORY_SEPARATOR . 'isRunning.sh' ;
$storageBinary  = $scriptsBase . DIRECTORY_SEPARATOR . 'storagenode' ;
$yamlPath = $scriptsBase . DIRECTORY_SEPARATOR . 'docker-compose_base.yml' ;
logMessage("------------------------------------------------------------------------------");
logMessage("Platform Base($platformBase), ModuleBase($moduleBase) scriptBase($scriptsBase)");
# ------------------------------------------------------------------------


$output = "";

if(isset($_POST['isajax']) && ($_POST['isajax'] == 1)) {
    logMessage("config called up with isajax 1 ");
    logEnvironment() ;


    $_address  = $_POST["address"];
    $_server   = $_POST["server"];
    $_api  = $_POST["api"];
    $_satellite      = $_POST["satellite"];
    $_encryptionPassphrase = $_POST['encryptionPassphrase'];
   
    //Changing permissions of the shell script
    shell_exec("chmod 777 $startScript 2>&1");
    shell_exec("chmod 777 $stopScript 2>&1");
    

    $output = shell_exec("/bin/bash $startScript $_address $_wallet $_emailId $_storage $_identity_directory $_directory 2>&1 ");

    $jsonString = file_get_contents($file);
    $data = json_decode($jsonString, true);
    $data['last_log'] = $output;
    $newJsonString = json_encode($data);
    file_put_contents($file, $newJsonString);


  }else if(isset($_POST['isConfig']) && ($_POST['isConfig'] == 1)){

    $_address  = $_POST["address"];
    $_server   = $_POST["server"];
    $_api  = $_POST["api"];
    $_satellite      = $_POST["satellite"];
    $_encryptionPassphrase = $_POST['encryptionPassphrase'];
  
    $properties = array(
      'Port'  => $_address,
      'Server Address'  => $_server,
      'API Key'=> $_api,
      'Satellite' => $_satellite,
      'Encryption Passphrase' => $_encryptionPassphrase,
      );
    file_put_contents($file, json_encode($properties));

    $content = file_get_contents($file);
    $properties = json_decode($content, true);

    logMessage("config called up with isStopAjax 1 ");
    $output = shell_exec("bash $stopScript 2>&1 ");

    /* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));

  }else if(isset($_POST['isUpdateAjax']) && ($_POST['isUpdateAjax'] == 1)){
    $content = file_get_contents($file);
    $properties = json_decode($content, true);

    logMessage("config called up with isUpdateAjax 1 ");
    $server_address = $_SERVER['SERVER_ADDR'] ;
    $output = shell_exec("/bin/bash $updateScript $file $_address $_wallet $_emailId $_storage $_identity_directory $_directory $server_address 2>&1 ");

    /* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));

  } else if(isset($_POST['isstartajax']) && ($_POST['isstartajax'] == 1)) {
    logMessage("config called up with isstartajax 1 ");
    $content = file_get_contents($file);
    $prop = json_decode($content, true);
    $output = "<br><b>LATEST LOG :</b> <br><code>" . $prop['last_log'] . "</code>";
    $output = preg_replace('/\n/m', '<br>', $output);
    if (!trim($output) == "") {
  echo $output;
    } else {
  echo $output;
    }
 } 

  // checking is storagenode is running.
  else if(isset($_POST['isrun']) && ($_POST['isrun'] == 1)) {
    $output = shell_exec("/bin/bash $isRunning ");
    logMessage("Run status of container is $output ");
    // echo $output ;
    echo 0 ;
  }

 else {
  // DEFAULT : Load contents at start
  logMessage("config called up with for loading ");
  //
  // checking if file exists.
  if(file_exists($file)){
  $content = file_get_contents($file);
  $prop = json_decode($content, true);
  logMessage("Loaded properties : " . print_r($prop, true));
  }

{

?>
<?php include 'header.php';?>
<style>
code {
        white-space: pre-wrap; /* preserve WS, wrap as necessary, preserve LB */
        /* white-space: pre-line; /* collapse WS, preserve LB */
}
</style>
<link href="./resources/css/config.css" type="text/css" rel="stylesheet">
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
          <?php
  // TODO: REMOVE this once this works OK
          if ( $output ){
          } else {

            $file1 = "${rootBase}/storagenode/ca.cert";
            $file2 = "${rootBase}/storagenode/ca.key";
            $file3 = "${rootBase}/storagenode/identity.cert";
            $file4 = "${rootBase}/storagenode/identity.key";
            $numFiles = `ls ${rootBase}/storagenode | wc -l ` ;
            $numFiles = (int) $numFiles ;

             if(
              ($numFiles == 6) and 
              file_exists($file1) and
              file_exists($file2) and
              file_exists($file3) and
              file_exists($file4)
              ) 
             {
                 echo "<span id='file_exists' style='display:none;'>0</span>";
              }else{
                  echo "<span id='file_exists' style='display:none;'>1</span>";
                }

          ?>
          <div class="col-10 config-page">
            <div class="container-fluid">
              <h2>Setup</h2>
              <a href="https://documentation.storj.io/" target="_blank"><p class="header-link">Documentation ></p></a>
                 
                <!-- <div style="display:none" id="storjrows"> -->
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon port-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Port Forwarding</h4>
                    <p class="segment-msg">Test</p>
                    <span id="externalAddressval"></span><span style="display:none;" id="editexternalAddressbtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#externalAddress">
                      Edit External Address
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#externalAddress" id="externalAddressbtn">
                      Add External Address
                    </button>
                    <div class="modal fade" id="externalAddress" tabindex="-1" role="dialog" aria-labelledby="externalAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Port Forwarding</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Host Address</p>
                          <input class="modal-input" id="host_address" name="host_address" type="text" class="quantity" placeholder="127.0.0.1:7777:7777" value="<?php if(isset($prop['Port'])) echo $prop['Port'] ?>"/>
                            <p class="host_token_msg msg" style="display:none;">Enter Valid Host Address</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_address">Set External Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon wallet-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Server Address</h4>
                    <p class="segment-msg">Test</p>
                    <span id="wallettbtnval"></span><span style="display:none;" id="editwallettbtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#walletAddress">
                        Edit Server Address
                      </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#walletAddress" id="addwallettbtn">
                      Add Server Address
                    </button>
                    <div class="modal fade" id="walletAddress" tabindex="-1" role="dialog" aria-labelledby="walletAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Server Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Server Address</p>
                            <input class="modal-input" name="Server Address" id="wallet_address" placeholder="0.0.0.0:7777" value="<?php if(isset($prop['Server Address'])) echo $prop['Server Address'] ?>"/>
                            <p class="wallet_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_wallet">Set Server Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon storage-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">API Key</h4>
                    <p class="segment-msg">Test</p>
                    <span id="storagebtnval"></span><span style="display:none;" id="editstoragebtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#storageAllocation">
                      Edit API Key
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#storageAllocation" id="addstoragebtn">
                      Set API Key
                    </button>
                    <div class="modal fade" id="storageAllocation" tabindex="-1" role="dialog" aria-labelledby="storageAllocation" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">API Key</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">API Key</p>
                            <input class="modal-input shorter" id="storage_allocate" name="storage_allocate" type="text" step="1" min="1" class="quantity" placeholder="Storj API Key" value="<?php if(isset($prop['API Key'])) echo $prop['API Key'] ?>"/>
                          <p class="storage_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="allocate_storage">Set API Key</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


               
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon email-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Satellite</h4>
                    <p class="segment-msg">Test</p>
                    <span id="emailAddressval"></span><span style="display:none;" id="editemailAddressbtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#emailAddress">
                      Edit Satellite
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#emailAddress" id="emailAddressbtn">
                      Add Satellite
                    </button>
                    <div class="modal fade" id="emailAddress" tabindex="-1" role="dialog" aria-labelledby="email_address" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Satellite</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Satellite</p>
                            <input class="modal-input" id="email_address" name="email_address" type="text" placeholder="<nodeid>@<address>:<port>" value="<?php if(isset($prop['Satellite'])) echo $prop['Satellite'] ?>"/>
                            <p class="email_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_emailaddress">Set Satellite</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon directory-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Encryption Passphrase</h4>
                    <p class="segment-msg">Test</p>
                      <span id="directorybtnval" cl></span><span style="display:none;" id="editdirectorybtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#directory">
                      Edit Encryption Passphrase
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#directory" id="adddirectorybtn">
                      Set Encryption Passphrase
                    </button>
                    <div class="modal fade" id="directory" tabindex="-1" role="dialog" aria-labelledby="directory" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="identity">Encryption Passphrase</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Encryption Passphrase</p>
                          <input style="width: 280px;" class="modal-input" id="storage_directory" name="storage_directory" placeholder="Encryption Passphrase" value="<?php if(isset($prop['Encryption Passphrase'])) echo $prop['Encryption Passphrase'] ?>"  />
                            <p class="directory_token_msg msg" style="display:none;position: relative;left: 34px;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_directory">Set Encryption Passphrase</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="bottom-buttons">
                   <button type="button" class="btn btn-primary configbtns" id="updatebtn">Update Gateway</button>
                  <div style="position: absolute;display: inline-block;left: 40%;">
                    <button type="button" disabled  id="stopbtn" class="btn btn-primary configbtns" style="cursor: not-allowed;">Configure Gateway</button>&nbsp;&nbsp;
                  <button type="button"  id="startbtn" class="btn btn-primary configbtns">Run Gateway</button>
                </div><br><br>
              <!-- log message -->
              <iframe>
                <p  id="msg"></p>
              </iframe>  
            </div>
          </div>
          <?php }
        } ?>
  </div>
<?php include 'footer.php';?>
<!--<script src="./resources/js/jquery-3.1.1.min.js"></script>-->
<script type="text/javascript" src="./resources/js/gateway.js"></script>
<?php

}

function logEnvironment() {
  logMessage(
    "\n----------------------------------------------\n"
    . "ENV is : " . print_r($_ENV, true)
    . "POST is : " . print_r($_POST, true)
    . "SERVER is : " . print_r($_SERVER, true)
    . "----------------------------------------------\n"
  );
}

function logMessage($message) {
    $file = "/var/log/STORJ" ;
    // $file = "test" ;
    $message = preg_replace('/\n$/', '', $message);
    $date = `date` ; $timestamp = str_replace("\n", " ", $date);
    file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
}

?>
