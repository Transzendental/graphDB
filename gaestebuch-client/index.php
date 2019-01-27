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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <style>
        .container {
            margin: auto;
            width: 80%;
            border: 1px solid black;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 10px;
        }
    </style>
    <script>
        var gbNeuesMitglied;
        var gbNeuerBeitrag;
        var gbNeuerKommentar = [];
    </script>
    <script type="module" src="js/gb-loader.js"></script>
    <script type="module" src="js/gb-kommentar.js"></script>
    <script type="module" src="js/gb-beitrag.js"></script>
    <script type="module" src="js/gb-neu-beitrag.js"></script>
    <script type="module" src="js/gb-neu-kommentar.js"></script>
    <script type="module" src="js/gb-neu-mitglied.js"></script>
    <script type="module" src="js/gb-loeschen-mitglied.js"></script>
</head>
<body>
    <div class="container" id="content">
        <h1>Gästebuch</h1>
        <hr>
        <img src="image/load.gif" border="0" width="50" id="loadLogo">
        <gb-loader></gb-loader>
    </div>
</body>
</html>