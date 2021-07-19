<?php
$amstaff = true;
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");
if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token'] OR !isset($_SESSION['staffId:light'])){
		returnJSON(array('t' => 'خطأ','m' => 'لقد حدث خطأ غير معروف حدث الصفحة ثم أعد المحاوله','tp' => 'error', 'b' => 'موافق'));
	}
	
	if(isset($_GET['cardStatus'],$_GET['cardId'])){
		$id = $_GET['cardId'];
		$status = $_GET['cardStatus'];
		if(antiSpam('payments.php:changeCardStatus')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		}elseif(!rankPermission($_SESSION['staffId:light'],5,true)){
			die();
		}elseif(!ctype_digit($id) or !ctype_digit($status) or strlen($id) > 30 or strlen($id) < 1 or $id < 1 or ($status != 1 and $status != 2)){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك تأكد من المدخلات','tp' => 'error', 'b' => 'موافق'));
		}else{
			$conn = $database->openConnection();
			$statement = $conn->prepare('SELECT status FROM cardsPayments WHERE id=:id');
			$statement->bindValue(':id',$id);
			$statement->execute();
			if($statement->rowCount() == 0){
				returnJSON(array('t' => 'خطأ','m' => 'لقد حدث خطأ غير معروف حدث الصفحة ثم أعد المحاوله','tp' => 'error', 'b' => 'موافق'));
			}elseif(current($statement->fetch()) == $status){
				returnJSON(array('t' => 'خطأ','m' => 'البطاقة فعلاً بهذه الحاله','tp' => 'error', 'b' => 'موافق'));
			}else{
				$statement = $conn->prepare('UPDATE cardsPayments SET status=:status WHERE id=:id');
				$statement->bindValue(':status',$status);
				$statement->bindValue(':id',$id);
				$statement->execute();
				returnJSON(array('t' => 'تم','m' => 'تم تغيير حالة البطاقة','tp' => 'success', 'b' => 'موافق'));
			}
		}
	}
	 
	if(isset($_GET['orderStatus'],$_GET['pid'])){
		$pid = $_GET['pid'];
		$status = $_GET['orderStatus'];
		if(antiSpam('payments.php:changeOrderStatus')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		}elseif(!rankPermission($_SESSION['staffId:light'],5,true)){
			die();
		}elseif(!ctype_digit($pid) or !ctype_digit($status) or strlen($pid) > 30 or strlen($pid) < 1 or $pid < 1 or $status < 1 or $status > 5){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك تأكد من المدخلات','tp' => 'error', 'b' => 'موافق'));
		}else{
			$conn = $database->openConnection();
			$statement = $conn->prepare('SELECT status FROM payments WHERE id=:pid');
			$statement->bindValue(':pid',$pid);
			$statement->execute();
			if($statement->rowCount() == 0){
				returnJSON(array('t' => 'خطأ','m' => 'لقد حدث خطأ غير معروف حدث الصفحة ثم أعد المحاوله','tp' => 'error', 'b' => 'موافق'));
			}elseif(current($statement->fetch()) == $status){
				returnJSON(array('t' => 'خطأ','m' => 'الطلب فعلاً بهذه الحاله','tp' => 'error', 'b' => 'موافق'));
			}else{
				$statement = $conn->prepare('UPDATE payments SET status=:status WHERE id=:pid');
				$statement->bindValue(':status',$status);
				$statement->bindValue(':pid',$pid);
				$statement->execute();
				switch($status){
					case 1:
						$status = "مكتمل";
					break;
					case 2:
						$status = "بطاقة خاظئة";
					break;
					case 3:
						$status = "جاري التنفيذ";
					break;
					case 4:
						$status = "في الإنتظار";
					break;
					case 5:
						$status = "ملغي‬‎";
					break;
				}
				returnJSON(array('t' => 'تم','m' => 'تم تغيير حالة البطاقة','tp' => 'success', 'b' => 'موافق','newStatus' => $status));
			}
		}
	}
	if(isset($_GET['checkout'])){
		$pid = $_GET['checkout'];
		if(antiSpam('payments.php:checkout')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		}elseif(!rankPermission($_SESSION['staffId:light'],5,true)){
			die();
		}elseif(!ctype_digit($pid) or strlen($pid) > 30 or strlen($pid) < 1 or $pid < 1 ){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك تأكد من المدخلات','tp' => 'error', 'b' => 'موافق'));
		}else{
			$conn = $database->openConnection();
			$statement = $conn->prepare('SELECT price,cid FROM payments WHERE id=:pid');
			$statement->bindValue(':pid',$pid);
			$statement->execute();
			if($statement->rowCount() == 0){
				returnJSON(array('t' => 'خطأ','m' => 'لقد حدث خطأ غير معروف حدث الصفحة ثم أعد المحاوله','tp' => 'error', 'b' => 'موافق'));
			}else{
				$stmt = $statement->fetch();
				$money = taxCalc($stmt['price'],0.15,2);
				$statement = $conn->query('UPDATE Customers SET Credits = Credits + '.$money.' WHERE id='.$stmt['cid']);
				$statement = $conn->prepare('UPDATE payments SET status=1 WHERE id=:pid');
				$statement->bindValue(':pid',$pid);
				$statement->execute();
				returnJSON(array('t' => 'تم','m' => 'تم تغيير حالة البطاقة','tp' => 'success', 'b' => 'موافق'));
			}
		}
	}
}
?>