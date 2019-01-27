<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 10.12.2018
 * Time: 21:35
 */

require_once "../service/mysqlConnect.php";
require_once "../service/response.php";
require_once "../service/transmit.php";

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    require_once "../service/options.php";
} else if($_SERVER['REQUEST_METHOD'] == "POST") {
//} else if($_SERVER['REQUEST_METHOD'] == "GET") {
    $raw_data = file_get_contents('php://input');
    //$raw_data = $_GET["json"];
    $json = json_decode($raw_data, false, 512);

    $db = mysqlConnect::getInstance();
    $db->updateBeitrag($json->nodeID, $json->mitgliedNodeID, $json->beitrag);

    response(array(
        true
    ));
}