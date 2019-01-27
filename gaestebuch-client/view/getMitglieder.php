<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 10.12.2018
 * Time: 10:53
 */

require_once "../service/mysqlConnect.php";
require_once "../service/response.php";
require_once "../service/transmit.php";
require_once "../model/Kommentar.php";
require_once "../model/Mitglied.php";

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    require_once "../service/options.php";
} else if($_SERVER['REQUEST_METHOD'] == "GET") {
    $db = mysqlConnect::getInstance();

    $mitgliederID = $db->getMitglieder();
    $return = array();
    foreach($mitgliederID AS $mitgliedID) {
        $mitglied = new Mitglied($mitgliedID);
        $return[] = $mitglied->toArray();
    }
    response($return);
}