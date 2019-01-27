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

        function getUrlVars() {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        }
    </script>
    <script type="module" src="js/gb-loader.js"></script>
    <script type="module" src="js/gb-kommentar.js"></script>
    <script type="module" src="js/gb-beitrag.js"></script>
    <script type="module" src="js/gb-neu-beitrag.js"></script>
    <script type="module" src="js/gb-neu-kommentar.js"></script>
    <script type="module" src="js/gb-neu-mitglied.js"></script>
    <script type="module" src="js/gb-loeschen-mitglied.js"></script>
    <script type="module" src="js/gb-mitglied-beschreibung.js"></script>
    <script type="module" src="js/gb-mitglied-beitraege.js"></script>
    <script type="module" src="js/gb-mitglied-kommentar.js"></script>
    <script type="module" src="js/gb-mitglied-mitglied.js"></script>
    <script type="module" src="js/gb-mitglied-von-mitglied.js"></script>
</head>
<body>
<div class="container" id="content">
    <h1>G&auml;stebuch: Mitglied</h1>
    <a href="index.php"><h3>Zurück zum G&auml;stebuch</h3></a>
    <gb-mitglied-beschreibung></gb-mitglied-beschreibung>
    <gb-mitglied-beitraege></gb-mitglied-beitraege>
    <gb-mitglied-kommentare-zu-mitglied></gb-mitglied-kommentare-zu-mitglied>
    <gb-mitglied-kommentare></gb-mitglied-kommentare>
    <gb-mitglied-kommentare-von-mitglied></gb-mitglied-kommentare-von-mitglied>
</div>
</body>
</html>