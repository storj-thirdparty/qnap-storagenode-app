<?php include 'header.php';?>
<style>
code {
        white-space: pre-wrap; /* preserve WS, wrap as necessary, preserve LB */
        /* white-space: pre-line; /* collapse WS, preserve LB */
}
</style>
<?php

# ------------------------------------------------------------------------
#  ASSUMPTIONS
# ------------------------------------------------------------------------
#  A) During install, access of rclone.json is set to rw-rw-rw-
#  B) A writeable rclone.conf file exists in web/conf folder 
#  C) web/conf folder is writeable by www user
#  D) rclone binary is DOWNLOADED & installed in web/scripts folder
#  E) web/data folder contains same hierarchy that is maintained on rclone
#    cloud contents  (sj://test/abc/def/... should have /abc/def in local folder )
#
# ------------------------------------------------------------------------
#  TO BE UPDATED
# ------------------------------------------------------------------------
# 1) After setting access in rclone.json, error message should be 
#    cleaned up (and a pop up message may be shown for command completion)
#    (Currently it seems like command is still running / in error)
# 2) At close (when update happens)
# 	"Set Access Key" LABEL is not changed to "Edit Access Key"
# 	(It is only updated next time)
# 3) For RUN Command, source and destination have to be passed as well
#
# ------------------------------------------------------------------------
#  Set environment variables
# ------------------------------------------------------------------------
$cfgfilename = "config.json";
$logfilename = "log.json";
$rclonefilename = "rclone.json";

$platformBase   = $_SERVER['DOCUMENT_ROOT'];
$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
$scriptsBase    = $moduleBase . '/scripts' ;
$confBase	= $moduleBase . '/conf' ;


$cfgfile        = $moduleBase  . DIRECTORY_SEPARATOR . $cfgfilename  ;
$logfile        = $moduleBase  . DIRECTORY_SEPARATOR . $logfilename  ;
$dataDir	= $moduleBase . DIRECTORY_SEPARATOR . "data" ;
$filename	= $moduleBase  . DIRECTORY_SEPARATOR . $rclonefilename  ;
$rcloneBin  	= $scriptsBase . DIRECTORY_SEPARATOR . 'rclone' ;
$rcloneCfg	= $confBase . DIRECTORY_SEPARATOR . 'rclone.conf' ;
# ------------------------------------------------------------------------
  $rcloneConfigName = "rcloneCfg1";

  $output = "";
  $content = file_get_contents($filename);
  $prop = json_decode($content, true);
  $data = array_values($prop);

  // Save accesskey in JSON file.
  if(isset($_POST['accesskey'])){
    logMessage("Processing name updation!");
    $jsonString = file_get_contents($filename);
    $data = json_decode($jsonString, true);
    $data['Access Key'] = $_POST['Access_Key'];
    $newJsonString = json_encode($data);
    file_put_contents($filename, $newJsonString);
    logMessage("name in file $filename updated #" . $newJsonString );
  }

  if(isset($_POST['rcloneconfig'])){
     logMessage("config called for rcloneconfig ");
     $name = $data[0] ;
     $command = sprintf("${rcloneBin} config create $rcloneConfigName storj scope %s --config $rcloneCfg ", $name);
     logMessage("Creating config using command $command \n");
     $output = shell_exec("$command 2>&1 ");
     logMessage("Output of command run: $output");

     $jsonString = file_get_contents($filename);
     $data = json_decode($jsonString, true);
     $data['last_log'] = $output;
     $newJsonString = json_encode($data);
     file_put_contents($filename, $newJsonString);

     # $content = file_get_contents($filename);
     # $prop = json_decode($content, true);
      $output = "<br><b>LATEST LOG :</b> <br><code>" . $data['last_log'] . "</code>";
      $output = preg_replace('/\n/m', '<br>', $output);
      if (!trim($output) == "") {
        echo $output;
      } else {
        echo $output;
      }
  }
  if(isset($_POST['runrclone'])){
      $access_key = $_POST['Access_Key'];
      # Validated that source and destination exists
      $source = $_POST['source'];
      $destination = $_POST['destination'];
      logMessage("config called up with runrclone 1 ");

      $properties = array(
      'Access Key'  => $access_key,
      'Source'  => $source,
      'Destination'  => $destination
      );
    file_put_contents($filename, json_encode($properties));

     $command = sprintf("${rcloneBin} copy $source ${rcloneConfigName}:$destination --config $rcloneCfg ");
     $output = "At time: " . `date ` . "Running command => $command \n" ;
     $result = shell_exec("$command 2>&1 ");
     if($result == "") {
     	$output .= "rclone copy ($source -> $destination was successful." ;
     } else {
      $output .= "(ERROR) rclone copy failed ! \n($result)";
     }

      $properties['last_log'] = $output;
      $newJsonString = json_encode($properties);
      file_put_contents($filename, $newJsonString);

      $output = "<b>LATEST LOG :</b> <br><code>" . $properties['last_log'] . "</code>";
      $output = preg_replace('/\n/m', '<br>', $output);   
      echo $output ;
    }
     if ( $output ){
          } else {
?>
<link href="./resources/css/config.css" type="text/css" rel="stylesheet">
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
          <div class="col-10 config-page">
            <div class="container-fluid">
              <h2>RClone</h2>
              <a href="https://documentation.storj.io/" target="_blank"><p class="header-link">Documentation ></p></a>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon name_icon"></div>

                  </div>
                  <div class="column col-md-10">
                    <h4 class="segment-title currentname">Access Key</h4>
                    <p class="segment-msg">Provide the Access key generated from Uplink.</p>
                    <span id="nameval"></span><span style="display:none;" id="editnamebtn"><button class="segment-btn" data-toggle="modal" data-target="#name">
                      Edit Access Key
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#name" id="namebtn">
                    Set Access Key
                    </button>
                    <div class="modal fade" id="name" tabindex="-1" role="dialog" aria-labelledby="name" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title">Access Key</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Access Key</p>
                            <input class="modal-input" type="text" id="current_name" name="current_name" placeholder="Access Key" value="<?php if(isset($data[0])) echo $data[0] ?>"/>
                            <p class="name_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_name"> Set Access Key</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon scope-icon"></div>

                  </div>
                  <div class="column col-md-10">
                    <h4 class="segment-title">Source</h4>
                    <p class="segment-msg"><!-- A brief detail about what is scope for and how it can be set. --> The local/source directory to be copied to the distributed network.</p>
                    <span id="scopeval"></span><span style="display:none;" id="editscopebtn">&nbsp;&nbsp;<button class="segment-btn" data-toggle="modal" data-target="#scope">
                      Edit Source
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#scope" id="scopebtn">
                    Set Source
                    </button>
                    <div class="modal fade" id="scope" tabindex="-1" role="dialog" aria-labelledby="scope" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title">Source</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Source</p>
                            <input class="modal-input" type="text" id="scope_val" name="scope_val" placeholder="Source" value="<?php if(isset($data[1])) echo $data[1] ?>"/>
                            <p class="scope_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="creat_scope"> Set Source</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon viewaccess-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Destination</h4>
                    <p class="segment-msg"><!-- Tell what is purpose of this flag. what does it enable or disable -->The target path on the network to copy the directory into.</p>
                    <span id="viewaccessval"></span><span style="display:none;" id="editviewaccessbtn">&nbsp;&nbsp;<button class="segment-btn" data-toggle="modal" data-target="#viewaccess">
                     Edit Destination
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#viewaccess" id="viewaccessbtn">
                      Set Destination
                    </button>
                    <div class="modal fade" id="viewaccess" tabindex="-1" role="dialog" aria-labelledby="viewaccess" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Destination</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Destination</p>
                           <input class="modal-input" type="text" id="viewaccess_val" name="viewaccess_val" placeholder="Destination" value="<?php if(isset($data[2])) echo $data[2] ?>"/>
                           <p class="viewaccess_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_viewaccess">Set Destination</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon defaults-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Defaults</h4>
                    <p class="segment-msg">Tell are defaults for? What kind of access impact they have</p>
                    <span id="defaultsval"></span>&nbsp;&nbsp;<span style="display:none;" id="editdefaultsbtn"><button class="segment-btn" data-toggle="modal" data-target="#defaults">
                        Edit Defaults
                      </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#defaults" id="defaultsbtn">
                      Set Defaults
                    </button>
                    <div class="modal fade" id="defaults" tabindex="-1" role="dialog" aria-labelledby="defaults" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Defaults</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Defaults</p>
                            <input class="modal-input" name="Defaults" id="defaults_val" placeholder="Defaults" value="<?php if(isset($data[3])) echo $data[3] ?>"/>
                            <p class="defaults_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_defaults">Set Defaults</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> --><br>
                <center><button class="btn btn-primary rclonebtns" id="rclonebtn"><i class="fa fa-spinner fa-spin rcloneloader"></i>Apply Configuration</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary rclonebtns" id="runrclonebtn"><i class="fa fa-spinner fa-spin runrcloneloader"></i>Run RClone</button></center><br>
                 <!-- log message -->
                <iframe>
                  <p  id="msg"></p>
                </iframe>  
            </div>
          </div>
        </div>
<?php include 'footer.php';?>
<script type="text/javascript" src="./resources/js/rclone.js"></script>

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
    $file = "/var/log/rclone.log" ;
    $message = preg_replace('/\n$/', '', $message);
    $date = `date` ; $timestamp = str_replace("\n", " ", $date);
    file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
}

?>
