<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 21:46
 */

require_once "../service/mysqlConnect.php";
require_once "../model/Beitrag.php";
require_once "../model/Mitglied.php";

function response($msg) {
    $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);

    $callback = filter_input(INPUT_GET, "callback", FILTER_SANITIZE_STRING);

    if(isset($callback)) {
        $msg = $callback."(".$msg.");";
    }

    die($msg);
}

error_reporting(E_ALL);

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
} else if($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = $_GET['ID'];

    $db = mysqlConnect::getInstance();

    $beitrag = new Beitrag($id);
    $mitgliedID = $db->getMitgliedByBeitrag($beitrag->getNodeID());
    $mitglied = new Mitglied($mitgliedID);
    $kommentare = $db->getKommentareByBeitrag($beitrag->getNodeID());

    response(array(
        "beitrag"       => $beitrag->toArray(),
        "mitglied"      => $mitglied->toArray(),
        "kommentare"    => $kommentare
    ));
}