<?php
ini_set('display_errors', 1);
if(!isset($req) || $req == false){
ini_set('session.cookie_httponly', true);
session_start();

require_once("db.php");
require_once("config.php");
require_once("functions.php");
if(!isset($withOutProtection)){
require_once("protection.php");
}
}
?>