<?php
$withOutProtection = true;
require_once("../inc/req.php");
require_once("../inc/config.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	
	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
	}else if (isset($_SESSION['memberId:light'])) {
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'أنت مسجل بالفعل','b' => true));
	} else if(isset($_POST['useroremail'],$_POST['password'],$_POST['reCAPTCHA'])){
		if(antiSpam("login:login.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
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
			returnJSON(array('tp' => 'error', 't' => 'خطأ','m' => 'رجاءَ تحقق من أنك لست روبوت','b' => true));
		}
		
		if(empty($_POST['useroremail']) OR empty($_POST['password'])){
			returnJSON(array('tp' => 'error', 't' => 'خطأ','m' => 'رجاءَ تحقق من أنك لست روبوت','b' => true));
			returnJSON(array('t' => 'خطأ','m' => 'تاكد من المدخلات..','tp' => 'error', 'b' => 'موافق'));
		} else if(strlen($_POST['useroremail']) > 260){
			returnJSON(array('t' => 'خطأ','m' => 'يجب إن لا يتعدى الاسم او البريد الإلكتروني 260 حرف.','s' => 'error', 'b' => true));
		} else if(strlen($_POST['password']) > 36 || strlen($_POST['password']) < 8){
			returnJSON(array('t' => 'خطأ','m' => 'يجب إن تكون كلمة مرورك أكبر من 8 إحرف ولا تتعدى 36 حرف.','tp' => 'error', 'b' => true));	
		} else {
			$conn = $database->openConnection();
			if (filter_var($_POST['useroremail'], FILTER_VALIDATE_EMAIL)) {
			$check = $conn->prepare("SELECT verify,username,password,id FROM Customers WHERE email=:email");
			$check->bindValue(":email", $_POST['useroremail']);
			$check->execute();
			}else{
			$check = $conn->prepare("SELECT verify,username,password,id FROM Customers WHERE username=:username");
			$check->bindValue(":username", $_POST['useroremail']);
			$check->execute();
			}
			if($check->rowCount() !== 0){
				$row = $check->fetch();
				$id = $row['id'];
				$nm = $row['username'];
				$verify = $row['verify'];
				$password = $row['password'];
				$passwordd = password_verify($_POST['password'], $password);
				if($passwordd){
					if($verify == 1){
						$_SESSION['memberId:light'] = $id;
						$_SESSION['clientnickname'] = $nm;
						returnJSON(array('t' => 'تم','m' => 'تم تسجيل الدخول بنجاح.','tp' => 'success', 'b' => false));	
					}else{
						returnJSON(array('t' => 'خطأ','m' => 'لم يتم تفعيل حسابك ،، راجع البريد الإلكتروني الخاص بك.','tp' => 'error', 'b' => true));	
					}
				}else{
					returnJSON(array('t' => 'خطأ','m' => 'تفاصيل تسجيل الدخول الخاصة بك خاطئة ، حاول مرة أخرى.','tp' => 'error', 'b' => true));	
				}
			}else{
				returnJSON(array('t' => 'خطأ','m' => 'تفاصيل تسجيل الدخول الخاصة بك خاطئة ، حاول مرة أخرى.','tp' => 'error', 'b' => true));	
			}
		}
	}
}
?>