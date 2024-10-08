<?php
require('conf/model.php');
require('conf/controller.php');
require_once("views/phpChart_Lite/conf.php");
require("views/PHPMailer-master/sendmail2.php");

define('SITENAME', "/Dectection_d_inondation");

$Url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$Url = str_replace(SITENAME, '', $Url);

switch ($Url) {
    case '/':
        showHome();
        break;
    default:
        showHome();  break;
}
?>