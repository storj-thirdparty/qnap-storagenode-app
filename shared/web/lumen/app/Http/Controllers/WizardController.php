<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WizardController extends Controller {

    /**
     * WizardController constructor.
     *
     */
    public function __construct() {
        
    }

    /**
     * Render the wizard page.
     *
     */
    public function index(Request $request) {
        $authPass = $request->cookie('authPass');
        $loginMode = json_decode(file_get_contents(base_path('data/logindata.json')), TRUE);
        $configFile = base_path('data/config.json');
        //TO DO Login redirect if the mode is on   
        if (file_exists($configFile)) {
            $content = file_get_contents($configFile);
            $prop = json_decode($content, true);
        }
        return view('wizard', compact('authPass', 'loginMode', 'prop'));
    }

    /**
     * Call for the list of directory
     * 
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

}
