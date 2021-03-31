<?php

namespace App\Helpers;

class IdentityHelper {

    public function checkIdentityProcessRunning($identityPidFile) {
        if (file_exists($identityPidFile)) {
            $pid = file_get_contents($identityPidFile);
            $pid = (int) $pid;

            // Figure out whether process is running
            $status = exec("if [ -d '/proc/$pid' ] ; then (echo 1 ; exit 0) ; else (echo 0 ; exit 2 ) ; fi");

            if ($status == 1) {
                // if process is running then print true.
                // echo true;
                return true;
            } else {
                // if process is not running then print false.
                // echo false;
                return false;
            }
        } else {
            // echo false;
            return false;
        }
    }

    public function killIdentityProcess($identityPidFile) {
        if (file_exists($identityPidFile)) {
            $pid = file_get_contents($identityPidFile);
            $pid = (int) $pid;
            // Stop Identity
            $output = shell_exec("kill -9 $pid");
            $msg = "Identity creation stopped (no identity generated)!\n$output";
        } else {
            $msg = "Identity creation process not found";
        }
        $this->logMessage($msg);
        echo $msg;
    }

    public function checkIdentityFileExistence($data) {
        // Checking file if exist or not.
        $identityFilePath = $data["Identity"] . "/storagenode/identity.key";
        if (validateExistence($data)) {
            $this->logMessage("(file_exist) File $identityFilePath and others already exist !");
            echo "0";   # NORMAL
        } else {
            $this->logMessage("(file_exist) File $identityFilePath or others don't exists !");
            echo "1";   # FILE NOT FOUND
        }
    }

    public function validateExistence($data) {
        if (!isset($data) || (!isset($data['Identity']))) {
            return False;
        }
        $Path = $data["Identity"] . "/storagenode";
        return $this->validatePathExistence($Path);
    }

    public function validatePathExistence($Path) {
        $fileList = [
            "ca.key",
            "identity.key",
            "ca.cert",
            "identity.cert"
        ];
        $allReqdFilesAvailable = 1;
        foreach ($fileList as $file) {
            if (!file_exists("$Path/$file")) {
                $allReqdFilesAvailable = 0;
            }
        }
        return $allReqdFilesAvailable;
    }

    public function identityExists($data) {
        #global $identityFilePath ;
        if (!isset($data) || (!isset($data['Identity']))) {
            return False;
        }
        $Path = $data["Identity"] . "/storagenode";
        $identityFilePath = "${Path}/identity.key";
        return file_exists($identityFilePath);
    }

    public function logEnvironment() {
        $this->logMessage("POST is : " . print_r($_POST, true));
    }

    public function logMessage($message) {
        $centralLogFile = env('CENTRAL_LOG_DIR', "/var/log/STORJ");
        $message = preg_replace('/\n$/', '', $message);
        $date = `date`;
        $timestamp = str_replace("\n", " ", $date);
        $timestamp .= " (identityLib)  ";
        file_put_contents($centralLogFile, $timestamp . $message . "\n", FILE_APPEND);
    }

    public function loadConfig($filePath) {
        return json_decode(file_get_contents($filePath), TRUE);
    }

    public function storeConfig($data, $filePath) {
        return file_put_contents($filePath, json_encode($data));
    }

    public function updateConfig($dataNew, $filePath) {
        $data = loadConfig($filePath);
        $data = array_merge($data, $dataNew);
        $this->storeConfig($data, $filePath);
    }

    public function updateConfigKey($key, $value, $filePath) {
        $data = loadConfig($filePath);
        $data[$key] = $value;
        $this->storeConfig($data, $filePath);
    }

}
