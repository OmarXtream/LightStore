<?php
$withOutProtection = true;
require_once("../inc/req.php");
require_once("../inc/vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	
	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
	}else if (isset($_SESSION['memberId:light'])) {
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'أنت مسجل بالفعل','b' => true));
	} else if(isset($_POST['email'],$_POST['reCAPTCHA'])){
		if(antiSpam("forgetmember:pass")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 'tp'=>'error', 'b'=>'موافق'));
		}
		$post = http_build_query(
			array (
				'response' => $_POST['reCAPTCHA'],
				'secret' => $Config["SecreTreCAPTCHA"],
				'remoteip' => $_SERVER['REMOTE_ADDR']
			)
		);
		$opts = array('http' => 
				array (
					'method' => 'POST',
					'header' => 'application/x-www-form-urlencoded',
					'content' => $post
				)
		);
		$context = stream_context_create($opts);
		$response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
		$result = json_decode($response);
		if (!$result -> success) {
			returnJSON(array('tp' => 'error', 't' => 'خطأ','m' => 'رجاءَ تحقق من أنك لست روبوت','b' => true, 'updateCAPTCHA' => true));
		}
		if(mb_strlen($_POST['email']) > 128) {
			returnJSON(array('t'=>'خطأ','m'=>'عذرًا، لم يتم قبول البريد الإلكتروني', 'tp'=>'error', 'b'=>'موافق'));
		}elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			returnJSON(array('t'=>'خطأ','m'=>'عذرًا، لم يتم قبول البريد الإلكتروني', 'tp'=>'error', 'b'=>'موافق'));
		}
		
			$conn=$database->openConnection();
			
			$stmt=$conn->prepare("SELECT timeresetpass FROM Customers WHERE email = :email");
			$stmt->bindValue(":email", $_POST['email']);
			$stmt->execute();

			if($stmt->rowCount() > 0){
				$antiSpam = current($stmt->fetch());
				if($antiSpam < time()){
					$time = time() + 259200;
					$verficationToken=bin2hex(openssl_random_pseudo_bytes(22));
					$sql = $conn->prepare('UPDATE Customers SET timeresetpass='.$time.',resetpass=1,verifyCode="'.$verficationToken.'" WHERE email = :email');
					$sql->bindValue(":email", $_POST['email']);
					$sql->execute();
					if($sql->rowCount() > 0){
						$body="<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Light | Forget Pass</title></head><body dir='rtl'><p>مرحباً</p><p>لإعادة تعيين كلمة مرورك من فضلك إضغط على الزر.</p><p><a href='https://www.".$Config['domain']."/verify?t={$verficationToken}' target='_blank' rel='noopener noreferrer' style='display:block; text-decoration:none; text-align:center; border-radius:5px; padding:16px 20px; color:rgb(255,255,255); max-width:200px; line-height:16px; font-size:14px; background-color:rgb(0,176,116); margin:0 auto'>إعادة تعيين كلمة مروري</a></p></body></html>";
						$mail = new PHPMailer();
						$mail->CharSet = 'UTF-8';
						$mail->isSMTP();
						$mail->Host = $Config['Mailhost'];
						$mail->SMTPAuth = true;
						$mail->Username = $Config['MailUserName'];
						$mail->Password = $Config['MailPassword'];
						$mail->SMTPSecure = 'tls';
						$mail->Port = $Config['MailPort'];
						$mail->setFrom("support@".$Config['domain']."", 'Light');
						$mail->addAddress($_POST['email'], '');
						$mail->isHTML(true);
						$mail->Subject = 'إعادة تعيين كلمة مرورك';
						$mail->MsgHTML($body);
								
						if(!$mail->send()) {
							returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ إثناء إرسالة رسالة التحقق.', 'tp'=>'error', 'b'=>'موافق'));
						} else {
							returnJSON(array('t'=>'حسناً','m'=>'تم إرسال رسالة التحقق، راجع البريد الإلكتروني.', 'tp'=>'success', 'b'=>'موافق'));
						}
					} else {
						returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ غير متوقع', 'tp'=>'error', 'b'=>'موافق'));
					}
				} else {
					returnJSON(array('t'=>'خطأ','m'=>'يرجئ الانتظار ثلاث ايام لتتمكن من إرسال رسالة تحقق اخرى', 'tp'=>'error', 'b'=>'موافق'));
				}
			} else {
				returnJSON(array('t'=>'خطأ','m'=>'لم يتم العثور على البريد الإلكتروني بالنظام', 'tp'=>'error', 'b'=>'موافق'));
			}
	}
}
?>