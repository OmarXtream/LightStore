<?php
require_once("inc/db.php");
require_once("inc/vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_GET['t'])) {
	if(mb_strlen($_GET['t']) < 40 OR mb_strlen($_GET['t']) > 80) {
		exit(header('Location: forgetpass'));
	}
	$conn = $database->openConnection();
	$check = $conn->prepare('SELECT timeresetpass,email FROM Customers WHERE verifyCode = :code');
	$check->bindValue(":code", $_GET['t']);
	$check->execute();
	
	if($check->rowCount() > 0){
		$data = $check->fetch();
		$time = $data['timeresetpass'];
		$email = $data['email'];
		if($time > time()){
			$timeTo = time() + 259200;
			$password = bin2hex(openssl_random_pseudo_bytes(4));
			$passwordhash = password_hash($password, PASSWORD_DEFAULT);
			$changeIt = $conn->prepare('UPDATE Customers SET password = "'.$passwordhash.'",timeresetpass = '.$timeTo.',verifyCode = ""  WHERE verifyCode = :code');
			$changeIt->bindValue(":code", $_GET['t']);
			$changeIt->execute();
			if($changeIt->rowCount() > 0){
				$body="<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Light | كلمة المرور</title></head><body dir='rtl'><p>مرحباً</p><p>كلمة مرورك الجديدة : ".$password."</p></body></html>";
				$mail = new PHPMailer();
				$mail->CharSet = 'UTF-8';
				$mail->isSMTP();
				$mail->Host = "support@Light.com";
				$mail->SMTPAuth = true;
				$mail->Username = "support@Light.com";
				$mail->Password = "mjwz9bgckueh";
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				$mail->setFrom("support@Light.com", 'Light');
				$mail->addAddress($email, '');
				$mail->isHTML(true);
				$mail->Subject = 'كلمة مرورك الجديدة';
				$mail->MsgHTML($body);
	
				if(!$mail->send()) {
					exit(header('Location: login?status=2'));
				} else {
					exit(header('Location: login?status=1'));
				}
			}
		} else {
			exit(header('Location: login?status=3'));
		}
	} else { 
		exit(header('Location: login'));
	}
	
	
} else {
	exit(header('Location: login'));
}

?>