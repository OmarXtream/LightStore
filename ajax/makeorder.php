<?php
require_once("../inc/req.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}

	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
	    
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
		

	} else if(isset($_POST['name'],$_POST['more'],$_POST['note'],$_POST['id'])){
		if(antiSpam("makeorder:makeorder.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}

	if(empty($_POST['name']) OR empty($_POST['more']) OR empty($_POST['note']) OR empty($_POST['id'])){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'تاكد من المدخلات','b' => true));
	}elseif(!ctype_digit($_POST['id'])){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خلل والظاهر انك تعرفه','b' => true));



	}else{
		
		$conn=$database->openConnection();
		$stmt=$conn->prepare("SELECT id,pr_price,pr_type,pr_name FROM Sproducts WHERE id=:user");
		$stmt->bindValue(":user", $_POST['id']);
		$stmt->execute();
		$checked =$stmt->fetch(PDO::FETCH_ASSOC);

		$stmt2=$conn->prepare("SELECT id FROM orders WHERE pr_id=:user AND cl_id=:d");
		$stmt2->bindValue(":user", $_POST['id']);
		$stmt2->bindValue(":d", $_SESSION['memberId:light']);
		$stmt2->execute();
if($stmt2->rowCount() != 0){
    
    returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لقد قمت بطلب هذا المنتج مسبقا','b' => true));
    
}

        		$sender=$conn->query("SELECT Credits,username FROM Customers WHERE id={$_SESSION['memberId:light']}");
						if($sender->rowCount() > 0){
							$senderData=$sender->fetch(PDO::FETCH_ASSOC);
							$prname = $checked['pr_name'];
						}
		if($stmt->rowCount() != 0  AND $senderData["Credits"] >= $checked['pr_price'] ){

        $removeCredits = $conn->prepare('UPDATE Customers SET Credits= Credits - '.$checked['pr_price'].' 
          WHERE id=:iddd
                ');
        $removeCredits->bindValue(':iddd', $_SESSION['memberId:light'],  PDO::PARAM_INT);
        $removeCredits->execute();        
        $removeCredits->CloseCursor();

	if($removeCredits->rowCount() > 0){

			$stmtz=$conn->prepare("INSERT INTO orders (copyrights,more,note,cl_id,pr_id,pr_name,pr_type) VALUES (:name,:type,:describes,:price,:prid,:prnm,:prtype)");
			$stmtz->bindValue(":name", $_POST['name']);
			$stmtz->bindValue(":type", $_POST['more']);
			$stmtz->bindValue(":describes", $_POST['note']);
			$stmtz->bindValue(":price", (int)$_SESSION['memberId:light']);
			$stmtz->bindValue(":prid", (int)$_POST['id']);
			$stmtz->bindValue(":prnm", $prname);
			$stmtz->bindValue(":prtype", (int)$checked['pr_type']);


			$stmtz->execute();
			
			
			
			if($stmtz->rowCount() > 0){
			    
			    
			    
			    
			    
returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => 'قم بمراجعة قائمة مشترياتك بشكل دوري لطلبك ','b' => true));           
    }else{
returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'error recording !','b' => true));        
    }

}else{
    
            
		returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة', 's'=>'error', 'b'=>'موافق'));

    
}


}else{ 
    returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'لم يتم العثور على المنتج او ليس لديك رصيد كافي','b' => true)); }

		
		
		

	}
	
} else {}}
?>