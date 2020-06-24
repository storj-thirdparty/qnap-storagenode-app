<?php

  require_once("environment.php");

  logMessage("------------------------------------------------------------------------------");
  logMessage("Platform Base($platformBase), ModuleBase($moduleBase) scriptBase($scriptsBase)");
  # ------------------------------------------------------------------------


  $output = "";
  $data = json_decode(file_get_contents("php://input"), TRUE);
  $email = $data['email'];
  $address = $data['address'];
  $host = $data['host'];
  $storage = $data['storage'];
  $directory = $data['directory'];
  $identity = $data['identity'];

  if($data){

    $jsonString = file_get_contents($file);
    $data = json_decode($jsonString, true);

    $data['Identity'] = $identity;
    $data['Port'] = $host;
    $data['Wallet'] = $address;
    $data['Allocation'] = $storage;
    $data['Email'] = $email;
    $data['Directory'] = $directory;
    $newJsonString = json_encode($data);
    file_put_contents($file, $newJsonString);
  }


  if(filter_input(INPUT_POST, 'isajax') && filter_input(INPUT_POST, 'isajax') == 1) {
    logMessage("config called up with isajax 1 ");
    logEnvironment() ;

    $_address  = filter_input(INPUT_POST, 'address');
    $_wallet   = filter_input(INPUT_POST, 'wallet');
    $_storage  = filter_input(INPUT_POST, 'storage');
    $_emailId      = filter_input(INPUT_POST, 'email_val');
    $_directory      = filter_input(INPUT_POST, 'directory');
    $_identity_directory = filter_input(INPUT_POST, 'identity');
    $_authKey = filter_input(INPUT_POST, 'authKey');

    $properties = array(
    'Identity'	=> "$_identity_directory",
    'AuthKey'	=> $_authKey,
    'Port'	=> $_address,
    'Wallet'	=> $_wallet,
    'Allocation'=> $_storage,
    'Email'	=> $_emailId,
    'Directory' => "$_directory"
    );
    file_put_contents($file, json_encode($properties));
    $output = shell_exec("/bin/bash $startScript $_address $_wallet $_storage $_identity_directory/storagenode $_directory $_emailId 2>&1 ");

    /* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));

  }else if(filter_input(INPUT_POST, 'isstopAjax') && filter_input(INPUT_POST, 'isstopAjax') == 1) {
    $content = file_get_contents($file);
    $properties = json_decode($content, true);

    logMessage("config called up with isStopAjax 1 ");
    $output = shell_exec("bash $stopScript 2>&1 ");

    /* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));

     }else if(filter_input(INPUT_POST, 'isUpdateAjax') && filter_input(INPUT_POST, 'isUpdateAjax') == 1) {
    $content = file_get_contents($file);
    $properties = json_decode($content, true);

    logMessage("config called up with isUpdateAjax 1 ");
    $server_address = $_SERVER['SERVER_ADDR'] ;
    $output = shell_exec("/bin/bash $updateScript $file $_address $_wallet $_storage $_identity_directory $_directory $server_address $_emailId 2>&1 ");

    /* Update File again with Log value as well */
    $properties['last_log'] = $output ;
    file_put_contents($file, json_encode($properties));

    } else if(filter_input(INPUT_POST, 'isstartajax') && filter_input(INPUT_POST, 'isstartajax') == 1) {
    logMessage("config called up with isstartajax 1 ");
    $content = file_get_contents($file);
    $prop = json_decode($content, true);
    $output = "<br><b>LATEST LOG :</b> <br><code>" . $prop['last_log'] . "</code>";
    $output = preg_replace('/\n/m', '<br>', $output);
    echo trim($output);
  } 

  // checking is storagenode is running.
  else if(filter_input(INPUT_POST, 'isrun') && filter_input(INPUT_POST, 'isrun') == 1) {
    $output = shell_exec("/bin/bash $isRunning ");
    logMessage("Run status of container is $output ");
    echo $output ;
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

      // Identity Path
      $rootBase = $prop['Identity'] ;

      if($prop['Identity'] == "" && $prop['Identity'] == null && $prop['Port'] == "" && $prop['Port'] == null && $prop['Wallet'] == "" && $prop['Wallet'] == null && $prop['Allocation'] == "" && $prop['Allocation'] == null && $prop['Email'] == "" && $prop['Email'] == null && $prop['Directory'] == "" && $prop['Directory'] == null){
        header("Location: wizard.php");
      }

    }

  {

?>
<?php require_once('header.php');?>
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
    <?php require_once('menu.php'); ?>
      <?php
        // TODO: REMOVE this once this works OK
        if ( $output ){
        } else {

          if(file_exists($identityFile))
          {
            $pid = file_get_contents($identityFile);
            $pid = (int)$pid ;

            // Figure out whether process is running
            $status = exec("if [ -d '/proc/$pid' ] ; then (echo 1 ; exit 0) ; else (echo 0 ; exit 2 ) ; fi");

            if($status == 1) {
              // if process is running then print true.
              echo "<span id='identityfile' style='display:none;'>true</span>";
            } else {
              // if process is not running then print false.
              echo "<span id='identityfile' style='display:none;'>false</span>";
            }

          }else{
            echo "<span id='identityfile' style='display:none;'>false</span>";
          }

          $file1 = "${rootBase}/storagenode/ca.cert";
          $file2 = "${rootBase}/storagenode/ca.key";
          $file3 = "${rootBase}/storagenode/identity.cert";
          $file4 = "${rootBase}/storagenode/identity.key";
          $numFiles = `ls ${rootBase}/storagenode | wc -l ` ;

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
          <div class="row segment" id="identityrow">
            <div class="column col-md-2">
              <div class="segment-icon identity_icon"></div>
          </div>
          <div class="column col-md-10">
            <h4 class="segment-title">Identity</h4>
            <p class="segment-msg">Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS.</p>

            <span style="display:none;" id="editidentitybtn"><button class="segment-btn" data-toggle="modal" data-target="#identity">
            Edit Authorization Token
            </button></span>
            <button class="segment-btn" data-toggle="modal" data-target="#identity" id="identitybtn">

            Enter Authorization Token
            </button>

            <br><br>

            <div id="identity_status" style="overflow: auto;"><B> LATEST LOG </B></div>

          <div class="modal fade" id="identity" tabindex="-1" role="dialog" aria-labelledby="identity" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">

                    <h5 class="modal-title">Identity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <p class="modal-input-title">Authorization Token</p>

                    <input class="modal-input" type="text" id="identity_token" name="identity_token" placeholder="your@email.com: 1BTJeyYWAquvfQWscG9VndHjyYk8PSzQvrJ5DC" value="<?php if(isset($prop['AuthKey'])) echo $prop['AuthKey'] ?>"/>

                    <p class="modal-input-title">Identity Path</p>

                    <input class="modal-input" type="text" id="identity_path" name="identity_path" placeholder="/path/to/identity" value="<?php if(isset($prop['Identity'])) echo $prop['Identity'] ?>" style="position: relative;left: 45px;margin-top: 15px;" />
                    <p class="identity_path_msg msg" style="display:;position: relative;left: 15px;">This is required Fields</p>


                    <span class="identity_note"><span>Note:</span> Creating identity can take several hours or even days, depending on your machines processing power & luck.</span>
                  </div>
                  <div class="modal-footer">
                    <button class="modal-btn" data-dismiss="modal">Close</button>
                    <!--  Replace Set Identity Path to Create Identity -->
                    <button class="modal-btn" id="create_identity"> Create Identity</button>
                    <button class="modal-btn" id="stop_identity" disabled style="cursor: not-allowed;"> Stop Identity</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <div class="row segment">
        <div class="column col-md-2"><div class="segment-icon port-icon"></div></div>
          <div class="column col-md-10 segment-content">
            <h4 class="segment-title">Port Forwarding</h4>
            <p class="segment-msg">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected.</p>
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
                    <input class="modal-input" id="host_address" name="host_address" type="text" class="quantity" placeholder="hostname.ddns.net:28967" value="<?php if(isset($prop['Port'])) echo $prop['Port'] ?>"/>
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
          <h4 class="segment-title">Ethereum Wallet Address</h4>
          <p class="segment-msg">In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address.</p>
          <span id="wallettbtnval"></span><span style="display:none;" id="editwallettbtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#walletAddress">
          Edit Wallet Address
          </button></span>
          <button class="segment-btn" data-toggle="modal" data-target="#walletAddress" id="addwallettbtn">
          Add Wallet Address
          </button>
          <div class="modal fade" id="walletAddress" tabindex="-1" role="dialog" aria-labelledby="walletAddress" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Ethereum Wallet Address</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="modal-input-title">Wallet Address</p>
                  <input class="modal-input" name="Wallet Address" id="wallet_address" placeholder="Enter Wallet Address" value="<?php if(isset($prop['Wallet'])) echo $prop['Wallet'] ?>"/>
                  <p class="wallet_token_msg msg" style="display:none;">This is required Field</p>
                </div>
                <div class="modal-footer">
                  <button class="modal-btn" data-dismiss="modal">Close</button>
                  <button class="modal-btn" id="create_wallet">Set Wallet Address</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>


    <div class="row segment">
      <div class="column col-md-2"><div class="segment-icon storage-icon"></div></div>
        <div class="column col-md-10 segment-content">
          <h4 class="segment-title">Storage Allocation</h4>
          <p class="segment-msg">How much disk space you want to allocate to the Storj network</p>
          <span id="storagebtnval"></span><span style="display:none;" id="editstoragebtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#storageAllocation">
          Edit Storage Capacity
          </button></span>
          <button class="segment-btn" data-toggle="modal" data-target="#storageAllocation" id="addstoragebtn">
          Set Storage Capacity
          </button>
          <div class="modal fade" id="storageAllocation" tabindex="-1" role="dialog" aria-labelledby="storageAllocation" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Storage Allocation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="modal-input-title">Storage Allocation</p>
                  <input class="modal-input shorter" id="storage_allocate" name="storage_allocate" type="number" step="1" min="1" class="quantity" placeholder="Please enter only valid number" value="<?php if(isset($prop['Allocation'])) echo $prop['Allocation'] ?>"/>
                  <p class="modal-input-metric">GB</p>
                  <p class="storage_token_msg msg" style="display:none;">Minimum 500 GB is required</p>
               </div>
              <div class="modal-footer">
                <button class="modal-btn" data-dismiss="modal">Close</button>
                <button class="modal-btn" id="allocate_storage">Set Storage Capacity</button>
              </div>
            </div>
          </div>
       </div>
      </div>
    </div>


    <div class="row segment">
      <div class="column col-md-2"><div class="segment-icon email-icon"></div></div>
        <div class="column col-md-10 segment-content">
          <h4 class="segment-title">Email Address</h4>
          <p class="segment-msg">Email address so that you can recieve notification you when a new version is  released.</p>
          <span id="emailAddressval"></span><span style="display:none;" id="editemailAddressbtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#emailAddress">
          Edit Email Address
          </button></span>
          <button class="segment-btn" data-toggle="modal" data-target="#emailAddress" id="emailAddressbtn">
          Add Email Address
          </button>
          <div class="modal fade" id="emailAddress" tabindex="-1" role="dialog" aria-labelledby="email_address" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Email Address</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="modal-input-title">Email Address</p>
                  <input class="modal-input" id="email_address" name="email_address" type="email" placeholder="Email Address" value="<?php if(isset($prop['Email'])) echo $prop['Email'] ?>"/>
                  <p class="email_token_msg msg" style="display:none;">Enter a Valid Email address</p>
                </div>
                <div class="modal-footer">
                  <button class="modal-btn" data-dismiss="modal">Close</button>
                  <button class="modal-btn" id="create_emailaddress">Set Email Address</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>


    <div class="row segment">
      <div class="column col-md-2"><div class="segment-icon directory-icon"></div></div>
        <div class="column col-md-10 segment-content">
          <h4 class="segment-title">Storage Directory</h4>
          <p class="segment-msg">The local directory where you want files to be stored on your hard drive for the network</p>
          <span id="directorybtnval"></span><span style="display:none;" id="editdirectorybtn"><button class="segment-btn editbtn" data-toggle="modal" data-target="#directory">
          Edit Storage Directory
          </button></span>
          <button class="segment-btn" data-toggle="modal" data-target="#directory" id="adddirectorybtn">
          Set Storage Directory
          </button>
          <div class="modal fade" id="directory" tabindex="-1" role="dialog" aria-labelledby="directory" aria-hidden="true">
           <div class="modal-dialog" role="document">
              <div class="modal-content">
                 <div class="modal-header">
                    <h5 class="modal-title" id="identity">Storage Directory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p class="modal-input-title">Storage Directory</p>
                    <input class="modal-input" id="storage_directory" name="storage_directory" placeholder="/path/to/folder_to_share" value="<?php if(isset($prop['Directory'])) echo $prop['Directory'] ?>"  />
                    <p class="directory_token_msg msg" style="display:none;">This is required Field</p>
                  </div>
                  <div class="modal-footer">
                    <button class="modal-btn" data-dismiss="modal">Close</button>
                    <button class="modal-btn" id="create_directory">Set Directory</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



      <div class="bottom-buttons">
        <button type="button" class="btn btn-primary configbtns" id="updatebtn">Update My Storage Node</button>
        <div style="position: absolute;display: inline-block;left: 40%;">
          <button type="button" disabled  id="stopbtn" class="btn btn-primary configbtns" style="cursor: not-allowed;">Stop My Storage Node</button>&nbsp;&nbsp;
          <button type="button"  id="startbtn" class="btn btn-primary configbtns">Start My Storage Node</button>
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

  <div class="container">
    <!-- The Modal -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Config</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            <p>Identity creating at <b>/root/.local/share/storj/identity/storagenode</b></p>
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>

  </div>

  <?php require_once('footer.php');?>
  <script type="text/javascript" src="./resources/js/config.js"></script>
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
