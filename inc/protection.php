<?php

if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

if(!isset($_SESSION['memberId:light'])){
	exit(header('Location: ./login'));
}


if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if(!isset($_SESSION['last_ip'])){
	$_SESSION['last_ip']=$_SERVER['REMOTE_ADDR'];
}

if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']){
	session_unset();
	session_destroy();
	exit(header("Refresh:0; url=./login"));
}

$inactive = 1800; // default 1800 s 
if( !isset($_SESSION['timeout']) )
$_SESSION['timeout'] = time() + $inactive; 

$session_life = time() - $_SESSION['timeout'];

if($session_life > $inactive)
{ 
	session_unset();
	session_destroy();
	exit(header("Refresh:0; url=./login?timeout=1"));
}

$_SESSION['timeout']=time();



?>