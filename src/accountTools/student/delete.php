<?php

/** 
 * Delete a student
 * 
 * PHP version 8.1
 * 
 * @file     /src/accountTools/student/delete.php
 * @category Managment
 * @package  VirtualPass
 * @author   Jack <duedot43@noreplay-github.com>
 * @license  https://mit-license.org/ MIT
 * @link     https://github.com/Duedot43/VirtualPass
 */
require "../../include/modules.php";


$config = parse_ini_file("../../../config/config.ini");
echo '<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delete a student</title>
    <meta name="color-scheme" content="dark light">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/public/style.css" type="text/css" />
    <link rel="icon" href="/public/favicon.ico" />
</head>';
if (!isset($_GET['user'])) {
    echo "Your user is not set";
    exit();
}
if (!userExists($config['sqlUname'], $config['sqlPasswd'], $config['sqlDB'], preg_replace("/[^0-9.]+/i", "", $_GET['user']))) {
    echo "That user does not exist!";
    exit();
}
//Auth
if (isset($_COOKIE['adminCookie']) and adminCookieExists($config['sqlUname'], $config['sqlPasswd'], $config['sqlDB'], preg_replace("/[^0-9.]+/i", "", $_COOKIE['adminCookie']))) {
    $output = sendSqlCommand("DELETE FROM users WHERE sysID='" . htmlspecialchars(preg_replace("/[^0-9.]+/i", "", $_GET['user']),  ENT_QUOTES, 'UTF-8') . "';", $config['sqlUname'], $config['sqlPasswd'], $config['sqlDB']);
    if ($output[0] == 1) {
        echo "Something went wrong with deleting the user!<br><button onclick=\"AJAXGet('/accountTools/student', 'mainEmbed')\" >Return home</button>";
        exit();
    }
    echo "Success! User deleted!<br><button onclick=\"AJAXGet('/accountTools/student', 'mainEmbed')\" >Return home</button>";
    exit();
} else {
    if (isset($_COOKIE['adminCookie'])) {
        header("Location: /admin/");
        exit();
    } else {
        header("Location: /teacher/");
        exit();
    }
}
