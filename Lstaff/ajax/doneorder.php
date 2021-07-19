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
		

	} else if(isset($_POST['order'],$_POST['pr'])){
		if(antiSpam("doneorder:doneorder.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}
$filename = '../../Lstorage/'.$_POST['pr'].'';

	if(empty($_POST['order']) OR empty($_POST['pr'])){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'تاكد من المدخلات','b' => true));
	}elseif(!ctype_digit($_POST['order'])){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خلل والظاهر انك تعرفه','b' => true));

	}elseif(!file_exists($filename)){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على الملف','b' => true));


	}else{

		$conn=$database->openConnection();
		$stmt2=$conn->prepare("SELECT cl_id,pr_name FROM orders WHERE id=:user");
		$stmt2->bindValue(":user", (int)$_POST['order']);
		$stmt2->execute();
		

		if($stmt2->rowCount() != 0){
	$d =$stmt2->fetch(PDO::FETCH_ASSOC);


$randcode = rand_code(25);
        $stmt= $conn->prepare('INSERT IGNORE INTO owners ( pr_name, id_cl, file,create_time,code) 
                             VALUES (:nm,  :cl, :f, '.time().',:cd)
                           ');
        $stmt->bindValue(':nm', $d['pr_name'], PDO::PARAM_STR);
        $stmt->bindValue(':cl', (int)$d['cl_id'], PDO::PARAM_INT);
        $stmt->bindValue(':f', $_POST['pr'], PDO::PARAM_STR);
        $stmt->bindValue(':cd', $randcode, PDO::PARAM_STR);



        $stmt->execute();        
        $stmt->CloseCursor();
        



			if($stmt->rowCount() > 0){
			    
			    
	$stmt3 = $conn->prepare('DELETE FROM orders WHERE id = :ff');
	$stmt3->bindValue(":ff", (int)$_POST['order']);
    $stmt3->execute();
    $stmt3->CloseCursor(); 
	
				if($stmt3->rowCount() > 0){


			
returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => 'تم تسليم الطلب بنجاح ','b' => true));
           
				}else{
returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'خطا في إنهاء الطلب','b' => true));
    }

           
           
           
    }else{
    
returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'خطا في تسليم الطلب','b' => true));

    }




}else{ returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على الطلب','b' => true)); }
		
		
		

	}
	
} else {}}
?>
