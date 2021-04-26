<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WizardController extends Controller {
    /*
     * Render the wizard page.
     */

    public function index(Request $request) {
        $authPass = $request->cookie('authPass');
        $loginMode = json_decode(file_get_contents(base_path('data/logindata.json')), TRUE);
        $configBase = env('CONFIG_DIR', "/share/Public/storagenode.conf");
        $configFile = "${configBase}/config.json";

        //Login redirect if the mode is on 
        if ((is_null($authPass) || $authPass == "0") && $loginMode['mode'] == "true") {
            $previous_location = $request->path();
            setcookie("previous_location", $previous_location, strtotime('+7 days'), "/"); // 86400 = 1 day
            return redirect('login');
        }
        if (file_exists($configFile)) {
            $content = file_get_contents($configFile);
            $prop = json_decode($content, true);
        } else {
            $prop = [];
        }
        return view('wizard', compact('authPass', 'loginMode', 'prop'));
    }

    /*
     * Call for the list of directory
     * Return list of directories
     */

    public function getDirectoryListing(Request $request) {
        $data = $request->request->get('data');
        $arr = array();
        //Change the variable below to set the default path
        $path = "/share/Public/";

        if (isset($data['action']) == null || isset($data['action']) == "") {
            $msg = "Invalid or Unknown API Request";
            $arr = array("error" => $msg);
        } else {
            if ($data['action'] !== "folders") {
                $msg = "Invalid or Unknown API Request";
                $arr = array("error" => $msg);
            } else {
                $dirs = array();
                if (isset($data['path'])) {
                    $path = $data['path'];
                }
                if (!is_dir($path)) {
                    $path = "/";
                }
                $dir = dir($path);
                while (false !== ($entry = $dir->read())) {
                    if ($entry != '.' && $entry != '..') {
                        if (is_dir($path . '/' . $entry)) {
                            $dirs[] = $entry;
                        }
                    }
                }
                $arr = array(
                    "cd" => $path,
                    "folders" => $dirs
                );
            }
        }
        echo json_encode($arr);
    }

    /*
     * Server Side Validation for the Email
     */

    public function validateemail(Request $request) {
        $validatedData = $this->validate($request, [
            'email' => 'required|email'
        ]);
    }
    
    /*
     * Server Side Validation for the Wallet Address
     */

    public function validateWalletAddress(Request $request) {
        $validatedData = $this->validate($request, [
             'address' => ['required', 'regex:/^0x[a-fA-F0-9]{40}$/i']
        ]);
    }
    
    /*
     * Server Side Validation for the Storage Directory Path
     */

    public function validateStorageDirectoryPath(Request $request) {
        $validatedData = $this->validate($request, [
             'directory' => ['required', 'regex:/^\/$|(\/[a-zA-Z_0-9-]+)+$/']
        ]);
    }
    
    /*
     * Server Side Validation for the Host entry
     */

    public function validateHost(Request $request) {
        $validatedData = $this->validate($request, [
             'host' => ['required', 'regex:/[^\:]+:[0-9]{5}/']
        ]);
    }

    /*
     * Save the Config Data
     */

    public function saveConfig(Request $request) {
        $data = $request->all();
        $validatedData = $this->validate($request, [
             'identity' => ['required', 'regex:/^\/$|(\/[a-zA-Z_0-9-]+)+$/']
        ]);
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

}
