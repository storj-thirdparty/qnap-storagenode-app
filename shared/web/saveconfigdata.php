<?php

$data = json_decode(file_get_contents("php://input"), TRUE);
$email = $data['email'];
$address = $data['address'];
$host = $data['host'];
$storage = $data['storage'];
$directory = $data['directory'];
$identity = $data['identity'];

if ($data) {
    $file = "config.json";
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
