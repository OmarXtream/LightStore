<?php
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}

	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
	    
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
		

	} else if(isset($_POST['name'],$_POST['dis'],$_POST['price'],$_POST['type'])){
		if(antiSpam("Sproduct:Sproduct.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}

	if(empty($_POST['name']) OR empty($_POST['dis']) OR empty($_POST['price']) OR empty($_POST['type'])){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'تاكد من المدخلات','b' => true));
	}elseif(!ctype_digit($_POST['price']) OR !ctype_digit($_POST['type'])  OR $_POST['type'] > 4 OR $_POST['type'] < 1){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خلل والظاهر انك تعرفه','b' => true));



	}else{
		
		$conn=$database->openConnection();
		$stmt=$conn->prepare("SELECT id FROM Sproducts WHERE pr_name=:user");
		$stmt->bindValue(":user", $_POST['name']);
		$stmt->execute();
		
		if($stmt->rowCount() == 0){


			$stmtz=$conn->prepare("INSERT INTO Sproducts (pr_name,pr_type,pr_des	,pr_price) VALUES (:name,:type,:describes,:price)");
			$stmtz->bindValue(":name", $_POST['name']);
			$stmtz->bindValue(":type", (int)$_POST['type']);
			$stmtz->bindValue(":describes", $_POST['dis']);
			$stmtz->bindValue(":price", (int)$_POST['price']);

			$stmtz->execute();
			
			if($stmtz->rowCount() > 0){

returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => 'تم إطلاق المنتج بنجاح !','b' => true));

           
    }else{
        returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'error recording File!','b' => true));   
    }





}else{ returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على المنتج او يوجد منتج بنفس الاسم','b' => true)); }


		
		
		

	}
	
} else {}}
?>