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
        return view('config', compact('authPass', 'loginMode', 'prop','dashboardurl'));
    }

    /**
     * Call for the config
     * 
     * Return list of directories
     */
    public function config(Request $request) {
        $data = $request->all();
        echo '<pre>';
        var_dump($data);exit;
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

}
