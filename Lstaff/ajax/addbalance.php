<?php
$amstaff = true;
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");
if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token']){
		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 'tp'=>'error','b'=>'موافق'));
	}

	if(isset($_GET['action'],$_GET['client'],$_GET['balance'],$_SESSION['staffId:light']) && $_GET['action'] == 'add'){
		if(antiSpam('addBalance:add')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		} else if(!rankPermission($_SESSION['staffId:light'],5,true)){
			returnJSON(array('t' => 'خطأ', 'm' => 'ليس لديك صلاحية لدخول هذه الصفحة', 'tp'=>'error'));
		}
		$conn=$database->openConnection();

		$isStaff=$conn->query('SELECT isStaff,rank FROM Customers WHERE id='.$_SESSION['staffId:light']);
		$dataS = $isStaff->fetch(PDO::FETCH_ASSOC);
		if($isStaff->rowCount() > 0 && $dataS["isStaff"] == 1 && $dataS["rank"] == 5){
		$max=1000;

		if(!filter_var($_GET['balance'], FILTER_VALIDATE_INT) OR strlen($_GET['client']) > 32 OR strlen($_GET['balance']) > 32){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ تأكد ان جميع المدخلات ارقام ،، و لاتتعدى ال32 حرف..','b'=>'موافق'));
		}

		if($_GET['balance'] > 0 && $_GET['balance'] < $max){

		$stmt=$conn->prepare('SELECT username,Credits FROM Customers WHERE id=:id');
		$stmt->bindParam(":id", $_GET['client'], PDO::PARAM_INT);
		$stmt->execute();

		if($stmt->rowCount() > 0){

			$data = $stmt->fetch(PDO::FETCH_ASSOC);

			if($data["Credits"] != $_GET['balance']){
			$update=$conn->prepare('UPDATE Customers SET Credits=:credit WHERE id=:id');
			$update->bindParam(':credit', $_GET['balance'], PDO::PARAM_INT);
			$update->bindParam(":id", $_GET['client'], PDO::PARAM_INT);
			$update->execute();

			if($update->rowCount() > 0) {
				$username=htmlspecialchars($data["username"]);
				$balance=htmlspecialchars($_GET['balance']);

				returnJSON(array('done' => true,'tp' => 'success', 't' => 'حسناً', 'm' => "تم إضافة المبلغ {$_GET["balance"]} إلى {$username} بنجاحً!",'b'=>'موافق','clientid' => $_GET['client'],'name' => $username,'balance' => $balance));
				
						} else {
							returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير متوقع،، حاول مرة اخرى.','b'=>'موافق'));
						}

			
					} else {
						returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'المبلغ الذي تريد إضافته مضاف فعلاً!','b'=>'موافق'));

					}

				} else {

					returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على الشخص ...','b'=>'موافق'));

				}

			}else{
				returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'يجب اختيار رقم من 0 إلى '.$max.' من فضلك.','b'=>'موافق'));
			}
		}else{
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'انت لست من طاقم الإدارة او ليس لديك صلاحية ..','b'=>'موافق'));

		}
	}
	



}



?>