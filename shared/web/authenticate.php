<?php

$data = json_decode(file_get_contents("php://input"), TRUE);

$cURLConnection = curl_init();


$host = "http://$_SERVER[HTTP_HOST]";


$url = $host . '/cgi-bin/authLogin.cgi?user=' . $data['username'] . '&pwd=' . $data['password'] . '&remme=1';

curl_setopt($cURLConnection, CURLOPT_URL, $url);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$curlResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

$xml = simplexml_load_string($curlResponse, 'SimpleXMLElement', LIBXML_NOCDATA);
$xmlJson = json_encode($xml);
$xmlArr = json_decode($xmlJson, 1);

$authPass = $xmlArr['authPassed'];

if ($authPass == "1") {
    setcookie("authPass", $authPass, time() + (86400 * 30), "/"); // 86400 = 1 day
    $arr = array(
        "authPass" => $authPass,
        "previous_location" => $_COOKIE['previous_location']
    );
} else {
    setcookie("authPass", $authPass, time() + (86400 * 30), "/"); // 86400 = 1 day
    $arr = array(
        "authPass" => $authPass,
        "previous_location" => $_COOKIE['previous_location']
    );
}
echo json_encode($arr);
