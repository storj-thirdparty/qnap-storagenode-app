<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\IdentityHelper;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IdentityController extends Controller {
    /* $identityHelper IdentityHelper */

    protected $identityHelper;

    /**
     * IdentityController constructor.
     *
     */
    public function __construct(IdentityHelper $identityHelper) {
        $this->identityHelper = $identityHelper;
    }

    /**
     * Return the details of the Identity.
     *
     */
    public function index(Request $request) {

        //  Set variables
        $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
        $scriptsBase = base_path('public/scripts');
        $identityGenBinary = env('IDENTITY_GEN_BINARY', "/share/Public/identity.bin/identity");
        $logFile = env('IDENTITY_LOG', "/share/Public/identity/logs/storj_identity.log");
        $data = $this->identityHelper->loadConfig("${configBase}/config.json");

        // Update config json file if updates provided
        $inputs = $request->all();
        
        if (isset($inputs['authkey']) || isset($inputs['identity'])) {
            // Saving Identity Path and Auth Key in JSON file.
            if (isset($inputs["authkey"])) {
                $data['AuthKey'] = $inputs["authkey"];
            }
            if (isset($inputs["identity"])) {
                $data['Identity'] = $inputs["identity"];
            }
            $this->identityHelper->storeConfig($data, "${configBase}/config.json");
        }


        $identityGenScriptPath = base_path('public/scripts/generateIdentity.sh');
        $Path = $data["Identity"] . "/storagenode";
        $identityFilePath = "${Path}/identity.key";
        $urlToFetch = env('IDENTITY_URL', "https://github.com/storj/storj/releases/latest/download/identity_linux_amd64.zip");
        $identitypidFile = base_path('public/identity.pid');
        $date = Date('Y-m-d H:i:s');
        $output = "";
        $configFile = "${configBase}/config.json";
        $inputs = $request->all();


        $this->identityHelper->logMessage("================== Identity Controller invoked ================== ");
        if (isset($inputs['createidval'])) {
            $this->identityHelper->logMessage("Identity php called for creation purpose identityString : " . filter_input(INPUT_POST, 'identityString'));
            if ($this->identityHelper->checkIdentityProcessRunning($identitypidFile) == true) {
                $this->identityHelper->logMessage("Identity process is already running!!\n");
                echo "Identity Process is already running!\n";
                return;
            } else {
                $this->identityHelper->logMessage("Identity process not found running, STARTING a new one!!\n");
            }
            
            // Saving Identity Path and Auth Key in JSON file.
            $data['AuthKey'] = $inputs['identityString'];
            $data['Identity'] = $inputs['identitypath'];
            $this->identityHelper->storeConfig($data, $configFile);

            if ($this->identityHelper->identityExists($data) && $this->identityHelper->validateExistence($data)) {
                $this->identityHelper->logMessage("Identity Key File and others already available");
                echo "Identity Key File and others already available";
                return;
            } else {
                $this->identityHelper->logMessage("Identity Key doesn't exists. Going to start identity generation ");
            }

            if (!isset($inputs['identityString'])) {
                $this->identityHelper->logMessage("Identity String not provided");
                echo "Identity String not provided";
                return;
            }

            $identityString = $inputs['identityString'];
            $this->identityHelper->logMessage("value of identityString($identityString)");
            $identityPath = $inputs['identitypath'];
            $this->identityHelper->logMessage("value of identityPath($identityPath)");

            $cmd = "$identityGenScriptPath $identityString $identityPath 2>&1 ";

            $programStartTime = Date('Y-m-d H:i:s');
            $this->identityHelper->logMessage("Launching command $cmd and capturing log in $logFile ");

            $process = new Process([$identityGenScriptPath, $identityString, $identityPath, " &"]);
            //$process->disableOutput();
            $process->run();
            $pid = $process->getPid();
            
             if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $processoutput = $process->getOutput();
            
            var_dump($processoutput);exit;

            $this->identityHelper->logMessage("Launched command (@ $programStartTime) ");
            $data['LogFilePath'] = $logFile;
            $data['idGenStartTime'] = $programStartTime;
            $this->identityHelper->updateConfig($data, $configFile);

            $file = escapeshellarg($logFile);
            $lastline = `tail -c160 $file | sed -e 's#\\r#\\n#g' | tail -1 `;


            $this->identityHelper->logMessage("Invoked identity generation program ($identityGenScriptPath) ");
            echo $lastline;
        } else if (isset($inputs['status'])) {
            $this->identityHelper->logMessage("Identity php called for fetching STATUS!");
            $data['LogFilePath'] = $logFile;
            $data['idGenStartTime'] = $date;
            $file = $data['LogFilePath'];
            $pid = file_get_contents($identitypidFile);
            $prgStartTime = $data['idGenStartTime'];
            $file = escapeshellarg($file);
            $lastline = `tail -c160 $file | sed -e 's#\\r#\\n#g' | tail -1 `;

            if ($this->identityHelper->identityExists($data) && $this->identityHelper->validateExistence($data)) {
                $this->identityHelper->logMessage("STATUS: Identity exists ! returning message");
                $this->identityHelper->logMessage("identity available at ${identityFilePath}");
                echo "identity available at $identityFilePath ";
            } else if ($lastline == "Done") { # EXACT Check to be figured out 
                $this->identityHelper->logMessage("STATUS: Identity generation completed. Returning message");
                $this->identityHelper->logMessage("identity available at ${identityFilePath}");
                echo "identity available at ${identityFilePath}";
            } else {
                $data = $this->identityHelper->loadConfig($configFile);
                $data['idGenStartTime'] = $date;
                $lastline = preg_replace('/\n$/', '', $lastline);
                $this->identityHelper->logMessage("STATUS: Identity generation in progress (LOG: $lastline)");
                echo "Identity generation STATUS($date):<BR> " .
                "Process ID: $pid , " .
                "Started at: " . $data['idGenStartTime'] . "<BR>" . $lastline;
                ?><div style="text-align: center"><img src="img/spinner.gif"></div><?php
            }
        } else if (isset($inputs['authkey']) || isset($inputs['identity'])) {
            $authkey = $inputs["authkey"];
            $identity = $inputs["identity"];
            $this->identityHelper->logMessage("Identity php called for creation purpose identityString : " . $authkey);
            if ($this->identityHelper->identityExists($data) && $this->identityHelper->validateExistence($data)) {
                $this->identityHelper->logMessage("Identity Key File and others already available");
                echo "Identity Key File and others already available";
                return;
            } else {
                $this->identityHelper->logMessage("Identity Key doesn't exists. Going to start identity generation ");
            }

            if (!isset($authkey)) {
                $this->identityHelper->logMessage("Identity String not provided");
                echo "Identity String not provided";
                return;
            }
            $identityString = $authkey;
            $this->identityHelper->logMessage("value of identityString($identityString)");
            $identityPath = $identity;
            $this->identityHelper->logMessage("value of identityPath($identityPath)");

            if ($this->identityHelper->checkIdentityProcessRunning($identitypidFile) == true) {
                $this->identityHelper->logMessage("Identity process is already running!!\n");
                echo "Identity Process is already running!\n";
                return;
            } else {
                $this->identityHelper->logMessage("Identity process not found running, STARTING a new one!!\n");
            }
            
            $cmd = "$identityGenScriptPath $identityString $identityPath > ${logFile} 2>&1 & "; 
            
            $programStartTime = Date('Y-m-d H:i:s');
            $this->identityHelper->logMessage("Launching command $cmd and capturing log in $logFile ");
            
            
            $process = new Process([$identityGenScriptPath, $identityString, $identityPath, " &"]);
            //$process->disableOutput();
            $process->run();
            $pid = $process->getPid();
            
             if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $processoutput = $process->getOutput();
            var_dump($processoutput);exit;
            $this->identityHelper->logMessage("Launched command (@ $programStartTime) ");
            $jsonString = file_get_contents($configFile);
            $data = json_decode($jsonString, true);
            $data['LogFilePath'] = $logFile;

            $data['idGenStartTime'] = $programStartTime;
            $newJsonString = json_encode($data);
            $file = $data['LogFilePath'];
            $file = escapeshellarg($file);
            $lastline = `tail -c 59 $file `;
            file_put_contents($configFile, $newJsonString);

            $this->identityHelper->logMessage("Invoked identity generation program ($identityGenScriptPath) ");
            echo "<b>Identity creation process is starting.</b><br>";
            ?><div style="text-align: center"><img src="img/spinner.gif"></div><?php
            echo $lastline;
        } else if (isset($data['identityCreationProcessCheck'])) {
            echo $this->identityHelper->checkIdentityProcessRunning($identitypidFile) ? "true" : "false";
        } else if (isset($inputs['fileexist'])) {
            $this->identityHelper->logMessage("Identity php called for finding file existence");
            $this->identityHelper->checkIdentityFileExistence($data);
        } else if (isset($inputs['isstopAjax'])) {
            // Stop Identity
            $this->identityHelper->killIdentityProcess($identitypidFile);
            exec("echo > $logFile ");
        } else {
            $this->identityHelper->logMessage("Identity php called (PURPOSE NOT CLEAR)!");
        }
        return;
    }

}
