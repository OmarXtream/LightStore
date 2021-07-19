<?php
if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.name','STAFFSESSID');
    ini_set('session.cookie_httponly', true);
	ini_set('session.cookie_domain', '.wse6.net');
    session_start();
}

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if(!isset($_SESSION['staffId:light'])){
	exit(header('Location: ./login'));
}

if(!isset($_SESSION['last_ip'])){
	$_SESSION['last_ip']=$_SERVER['REMOTE_ADDR'];
}

if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']){
	session_unset();
	session_destroy();
	exit(header("Location: ./login"));
}

$inactive = 1800;
if( !isset($_SESSION['timeout']) ) {
$_SESSION['timeout'] = time() + $inactive;
}

$session_life = time() - $_SESSION['timeout'];

if($session_life > $inactive)
{ 
	session_unset();
	session_destroy();
	header("Location: ./login?timeout=1");
	exit;
}

if(!rankPermission($_SESSION['staffId:light'],1))
{ 
	session_unset();
	session_destroy();
	header("Location: ./login?aaa");
	exit;
}

$_SESSION['timeout']=time();

?>