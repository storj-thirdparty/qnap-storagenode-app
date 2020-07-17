<?php

$data = json_decode(file_get_contents("php://input"), TRUE);

$cURLConnection = curl_init();


$host =  "http://$_SERVER[HTTP_HOST]";


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
    session_start();
    $_SESSION['authPass'] = $authPass;
    $_SESSION["authPass"] = $authPass;
    $arr = array(
        "authPass" => $authPass,
        "previous_location" => $_SESSION['previous_location']
    );
} else {
    session_start();
    $_SESSION['authPass'] = $authPass;
    $arr = array(
        "authPass" => $authPass,
        "previous_location" => $_SESSION['previous_location']
    );
}
echo json_encode($arr);
