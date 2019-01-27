<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 21:46
 */

require_once "../service/mysqlConnect.php";
require_once "../service/response.php";
require_once "../service/transmit.php";
require_once "../model/Mitglied.php";

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    require_once "../service/options.php";
} else if($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = $_GET['ID'];
    $nodeID = $_GET['nodeID'];

    $db = mysqlConnect::getInstance();

    if($id == null) {
        $id = $db->getMitgliedID($nodeID);
    }

    $mitglied = new Mitglied($id);

    response($mitglied->toArray());
}