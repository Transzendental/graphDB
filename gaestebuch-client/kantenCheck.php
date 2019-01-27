<?php
/**
 * Created by PhpStorm.
 * User: KWM
 * Date: 09.12.2018
 * Time: 15:41
 */

error_reporting( E_ALL );
ini_set('display_errors', 1);

require_once "model/Beitrag.php";
require_once "model/Kommentar.php";
require_once "model/Mitglied.php";

require_once "service/mysqlConnect.php";

$mysqli = mysqlConnect::getInstance();

$mysqli->getAllKanten();