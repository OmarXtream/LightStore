<?php
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");

if($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['pid']) and ctype_digit($_GET['pid'])){

	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token']){
		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 'tp'=>'error'));
	}
	
		if(isset($_GET['pid'],$_SESSION['staffId:light'])){

			if(antiSpam('Seditproduct:Seditproduct.php')){
				returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
			} else if(!rankPermission($_SESSION['staffId:light'],5,true) OR rankPermission($_SESSION['staffId:light'],1,true)){
				returnJSON(array('t'=>'خطأ','m'=>'عذرًا، لا تمتلك صلاحيات كافية','tp'=>'error','b'=>'موافق'));
			} else	if(!ctype_digit($_GET['pid']) OR strlen($_GET['pid']) > 32){
				returnJSON(array('t' => 'خطأ','m' => 'محاولة جيدة، نرجو عدم تكرارها','tp' => 'error','b' => 'موافق'));		
			}
					$conn = $database->openConnection();
		$stmt=$conn->prepare("SELECT pr_name FROM Sproducts WHERE id=:user");
		$stmt->bindValue(":user", (int)$_GET['pid']);
		$stmt->execute();
		
		if($stmt->rowCount() != 0){

	$stmt = $conn->prepare('DELETE FROM Sproducts WHERE id = :id');
    $stmt->bindValue(':id',(int)$_GET['pid'],PDO::PARAM_INT);
    $stmt->execute();
    $stmt->CloseCursor(); 
	
	if($stmt->rowCount() > 0){

returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => 'تم حذف المنتج بنجاح !','b' => true));
	}else{
				returnJSON(array('t'=>'خطأ', 'm'=>'فشلت العملية.', 'tp'=>'error'));

		
	}
		}else{
			
							returnJSON(array('t'=>'خطأ', 'm'=>'لم يتم العثور على المنتج.', 'tp'=>'error'));

		}
}
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}

	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
	    
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
		

	} else if(isset($_POST['name'],$_POST['id'],$_POST['dis'],$_POST['price'],$_POST['type'])){
		if(antiSpam("Seditproduct:Seditproduct.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}

	if(empty($_POST['name']) OR empty($_POST['dis']) OR empty($_POST['price']) OR empty($_POST['type'])){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'تاكد من المدخلات','b' => true));
	}elseif(!ctype_digit($_POST['price']) OR !ctype_digit($_POST['id']) OR !ctype_digit($_POST['type']) OR $_POST['type'] > 4 OR $_POST['type'] < 1){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خلل والظاهر انك تعرفه','b' => true));



	}else{

		$conn=$database->openConnection();
		$stmt=$conn->prepare("SELECT pr_name FROM Sproducts WHERE id=:user");
		$stmt->bindValue(":user", (int)$_POST['id']);
		$stmt->execute();
		
				$stmt2=$conn->prepare("SELECT id FROM Sproducts WHERE pr_name=:name");
		$stmt2->bindValue(":name", $_POST['name']);
		$stmt2->execute();

		if($stmt->rowCount() != 0 and $stmt2->rowCount() == 0){


$data = [
    'name' => $_POST['name'],
    'type' => (int)$_POST['type'],
    'describes' => $_POST['dis'],
    'price' => (int)$_POST['price'],
    'id' => (int)$_POST['id'],
];
$sql = "UPDATE Sproducts SET pr_name=:name,pr_type=:type,pr_des=:describes,pr_price=:price WHERE id=:id";
$stmtz= $conn->prepare($sql);
$stmtz->execute($data);



			if($stmtz->rowCount() > 0){
			    
returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => 'تم تعديل المنتج بنجاح !','b' => true));

           
    }else{
                returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'error recorded product!','b' => true));   

    }





}else{ returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على المنتج او يوجد منتج بنفس الاسم','b' => true)); }

		
		
		

	}
	
} else {}}
?>