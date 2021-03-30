<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller {

    /**
     * ConfigController constructor.
     *
     */
    public function __construct() {
        
    }

    /**
     * Render the config page.
     *
     */
    public function index(Request $request) {
        $authPass = $request->cookie('authPass');
        $loginMode = json_decode(file_get_contents(base_path('data/logindata.json')), TRUE);
        $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
        $configFile = "${configBase}/config.json";
        //TO DO Login redirect if the mode is on   
        if (file_exists($configFile)) {
            $content = file_get_contents($configFile);
            $prop = json_decode($content, true);
        }
        $port = ":14002";
        $url = "http://{$_SERVER['SERVER_NAME']}${port}";
        $escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $dashboardurl = $escaped_url;
        return view('config', compact('authPass', 'loginMode', 'prop', 'dashboardurl'));
    }

    /**
     * Save the Config Data
     * 
     */
    public function saveConfig(Request $request) {
        $data = $request->all();
        $email = $data['email'];
        $address = $data['address'];
        $host = $data['host'];
        $storage = $data['storage'];
        $directory = $data['directory'];
        $identity = $data['identity'];

        if ($data) {
            $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
            $file = "${configBase}/config.json";
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
        return true;
    }

    /**
     * Call for the checkRunningnode
     * 
     * Check if the node is running or not
     */
    public function checkRunningnode(Request $request) {
        $data = $request->all();
        if (isset($data['isrun']) && $data['isrun'] == 1) {
            $isRunning = base_path('public/scripts/isRunning.sh');
            $output = shell_exec("/bin/bash $isRunning ");
            $this->logMessage("Run status of container is $output ");
            echo $output;
        }
    }

    /**
     * Call for the isstartajax
     * 
     * log if the config called with isstartajax
     */
    public function isstartajax(Request $request) {
        $data = $request->all();
        if (isset($data['isstartajax']) && $data['isstartajax'] == 1) {
            $this->logMessage("config called up with isstartajax 1 ");
            $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
            $file = "${configBase}/config.json";
            $content = file_get_contents($file);
            $prop = json_decode($content, true);
            if (isset($prop['last_log'])) {
                $output = "<code>" . $prop['last_log'] . "</code>";
            } else {
                $output = "<code></code>";
            }
            $output = preg_replace('/\n/m', '<br>', $output);
            echo trim($output);
        }
    }

    /**
     * Call for the stopNode
     * 
     * Stopnode with shell script
     */
    public function stopNode(Request $request) {
        $data = $request->all();

        if (isset($data['isstopAjax']) && $data['isstopAjax'] == 1) {
            $stopScript = base_path('public/scripts/storagenodestop.sh');
            $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
            $file = "${configBase}/config.json";
            $content = file_get_contents($file);
            $properties = json_decode($content, true);

            $this->logMessage("config called up with isStopAjax 1 ");
            $output = shell_exec("bash $stopScript 2>&1 ");

            /* Update File again with Log value as well */
            $properties['last_log'] = $output;
            file_put_contents($file, json_encode($properties));
        }
    }

    /**
     * Call for the Startnode
     * 
     * Starnode node wit shell script
     */
    public function startNode(Request $request) {
        $data = $request->all();
        if (isset($data['isajax']) && $data['isajax'] == 1) {
            $startScript = base_path('public/scripts/storagenodestart.sh');
            $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
            $file = "${configBase}/config.json";
            $this->logMessage("config called up with isajax 1 ");
            $this->logEnvironment();

            $_address = $data['address'];
            $_wallet = $data['wallet'];
            $_storage = $data['storage'];
            $_emailId = $data['emailval'];
            $_directory = $data['directory'];
            $_identity_directory = $data['identity'];
            $_authKey = $data['authKey'];

            $properties = array(
                'Identity' => "$_identity_directory",
                'AuthKey' => $_authKey,
                'Port' => $_address,
                'Wallet' => $_wallet,
                'Allocation' => $_storage,
                'Email' => $_emailId,
                'Directory' => "$_directory"
            );
            file_put_contents($file, json_encode($properties));
            $output = shell_exec("/bin/bash $startScript $_address $_wallet $_storage $_identity_directory/storagenode $_directory $_emailId 2>&1 ");

            /* Update File again with Log value as well */
            $properties['last_log'] = $output;
            file_put_contents($file, json_encode($properties));
        }
    }

    /**
     * Call for the updateNode
     * 
     * updateNode node wit shell script
     */
    public function updateNode(Request $request) {
        $data = $request->all();
        if (isset($data['isUpdateAjax']) && $data['isUpdateAjax'] == 1) {
            $updateScript = base_path('public/scripts/storagenodeupdate.sh');
            $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
            $file = "${configBase}/config.json";
            $content = file_get_contents($file);

            $_address = $data['address'];
            $_wallet = $data['wallet'];
            $_storage = $data['storage'];
            $_emailId = $data['emailval'];
            $_directory = $data['directory'];
            $_identity_directory = $data['identity'];
            $_authKey = $data['authKey'];

            $properties = array(
                'Identity' => "$_identity_directory",
                'AuthKey' => $_authKey,
                'Port' => $_address,
                'Wallet' => $_wallet,
                'Allocation' => $_storage,
                'Email' => $_emailId,
                'Directory' => "$_directory"
            );
            file_put_contents($file, json_encode($properties));

            $this->logMessage("config called up with isUpdateAjax 1 ");
            $server_address = filter_input(INPUT_SERVER, 'SERVER_ADDR');
            $output = shell_exec("/bin/bash $updateScript $file $_address $_wallet $_storage $_identity_directory $_directory $server_address $_emailId 2>&1 ");

            /* Update File again with Log value as well */
            $properties['last_log'] = $output;
            file_put_contents($file, json_encode($properties));
        }
    }

    /**
     * Call for the setAuthswitch
     * 
     * set the data in the login.json file
     */
    public function setAuthswitch(Request $request) {
        $data = $request->all();
        $mode = $data['mode'];
        file_put_contents(base_path('data/logindata.json'), json_encode($_POST));
        echo json_encode($data);
    }

    /**
     * log message
     * 
     */
    public function logMessage($message = "") {
        $file = env('CENTRAL_LOG_DIR', "/var/log/STORJ");
        $message = preg_replace('/\n$/', '', $message);
        $date = `date`;
        $timestamp = str_replace("\n", " ", $date);
        file_put_contents($file, $timestamp . $message . "\n", FILE_APPEND);
    }

    /**
     * log message
     * 
     */
    public function logEnvironment() {
        global $_ENV;
        $this->logMessage(
                "\n----------------------------------------------\n"
                . "ENV is : " . print_r($_ENV, true)
                . "POST is : " . print_r($_POST, true)
                . "SERVER is : " . print_r($_SERVER, true)
                . "----------------------------------------------\n"
        );
    }

}
