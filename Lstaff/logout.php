<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.name','STAFFSESSID');
    ini_set('session.cookie_httponly', true);
	ini_set('session.cookie_domain', '.wse6.net');
    session_start();
}

if(isset($_SESSION['staffId:light'])){

	session_unset();
	session_destroy();
	header("Refresh:0; url=login");
	die;
	
}else{
	header("Refresh:0; url=login");
}
	
?>