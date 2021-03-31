<?php

$mode = $_POST['mode'];
file_put_contents("logindata.json", json_encode($_POST));
echo json_encode($_POST);
