<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller {
    /*
     * Render the login Page.
     */

    public function index(Request $request) {
        return view('login');
    }

    /*
     * Authenticate with QNAP username and password through API.
     */

    public function authenticate(Request $request) {
        $data = $request->all();
        $cURLConnection = curl_init();


        $host = "http://$_SERVER[HTTP_HOST]";
        $host = "http://173.225.183.161:8080";


        $url = $host . '/cgi-bin/authLogin.cgi?user=' . $data['username'] . '&pwd=' . $data['password'] . '&remme=1';

        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $curlResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $xml = simplexml_load_string($curlResponse, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlJson = json_encode($xml);
        $xmlArr = json_decode($xmlJson, 1);

        $username = $xmlArr['username'];

        if ($username == $data['username']) {
            setcookie("authPass", "1", time() + (86400 * 30), "/"); // 86400 = 1 day
            $arr = array(
                "authPass" => "1",
                "previous_location" => $request->cookie('previous_location')
            );
        } else {
            setcookie("authPass", "0", time() + (86400 * 30), "/"); // 86400 = 1 day
            $arr = array(
                "authPass" => "0",
                "previous_location" => $request->cookie('previous_location')
            );
        }
        echo json_encode($arr);
    }

}
