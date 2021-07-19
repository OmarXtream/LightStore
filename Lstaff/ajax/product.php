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
		

	} else if(isset($_POST['name'],$_POST['dis'],$_POST['price'],$_POST['type'],$_POST["file"],$_POST['m'])){
		if(antiSpam("product:product.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}
$filename = '../../Lstorage/'.$_POST['file'].'';

	if(empty($_POST['name']) OR empty($_POST['dis']) OR empty($_POST['price']) OR empty($_POST['type']) OR empty($_POST['m']) OR empty($_POST['file'])){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'تاكد من المدخلات','b' => true));
	}elseif(!ctype_digit($_POST['price']) OR !ctype_digit($_POST['type']) OR !ctype_digit($_POST['m']) OR $_POST['type'] > 4 OR $_POST['type'] < 1){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خلل والظاهر انك تعرفه','b' => true));

	}elseif(!file_exists($filename)){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على الملف','b' => true));


	}else{
		
		$conn=$database->openConnection();
		$stmt=$conn->prepare("SELECT id FROM products WHERE name=:user");
		$stmt->bindValue(":user", $_POST['name']);
		$stmt->execute();
		
		if($stmt->rowCount() == 0){


			$stmtz=$conn->prepare("INSERT INTO products (name,type,describes	,price,file,enc,create_time) VALUES (:name,:type,:describes,:price,:file,:m,".time().")");
			$stmtz->bindValue(":name", $_POST['name']);
			$stmtz->bindValue(":type", (int)$_POST['type']);
			$stmtz->bindValue(":describes", $_POST['dis']);
			$stmtz->bindValue(":price", (int)$_POST['price']);
			$stmtz->bindValue(":file", $_POST['file']);
			$stmtz->bindValue(":m", $_POST['m']);

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