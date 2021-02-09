<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once('vendor/autoload.php');
require_once('../src/Auth.php');
require_once('../src/Client.php');
require_once('../src/Helper.php');
require_once('../src/LeakSensor.php');

try {
    $CSID = getenv('CSID');
    $SecKey = getenv('SecKey');
    $targetDevice = getenv('targetDevice');
    $targetToken = getenv('targetToken');

    $YoLink_Auth = new \YoLink\Auth($CSID, $SecKey);

    \YoLink\LeakSensor::getState($YoLink_Auth, $targetDevice, $targetToken);
} catch (Exception $e) {
    throw new Exception($e);
}
