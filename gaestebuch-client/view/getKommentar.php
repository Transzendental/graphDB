<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 23:01
 */

require_once "../service/mysqlConnect.php";
require_once "../service/response.php";
require_once "../service/transmit.php";
require_once "../model/Kommentar.php";
require_once "../model/Mitglied.php";
require_once "../model/Beitrag.php";

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    require_once "../service/options.php";
} else if($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = $_GET['ID'];

    $db = mysqlConnect::getInstance();

    $kommentar = new Kommentar($id);
    $mitgliedID = $db->getMitgliedByKommentar($kommentar->getNodeID());
    $mitglied = new Mitglied($mitgliedID);
    $beitrag = new Beitrag($db->getBeitragByKommentar($kommentar->getNodeID()));

    response(array(
        "kommentar"         => $kommentar->toArray(),
        "mitglied"          => $mitglied->toArray(),
        "beitrag"           => $beitrag->toArray()
    ));
}