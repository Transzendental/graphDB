<?php
/**
 * Created by PhpStorm.
 * User: Kay Wilhelm Mähler
 * Date: 12.12.2018
 * Time: 10:15
 */

error_reporting(E_ALL);
//ini_set("display_errors", 1);

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}