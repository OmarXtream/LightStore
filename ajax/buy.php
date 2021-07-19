<?php
require_once("../inc/req.php");
$req = true;
if($_SERVER['REQUEST_METHOD'] == "POST"){

	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']
	OR !isset($_POST['id']) OR empty($_POST['id'])){
	    
		returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة', 's'=>'error', 'b'=>'موافق'));
	} else if(antiSpam("buy:buy.php")){
		returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
			} else	if(!ctype_digit($_POST['id']) OR strlen($_POST['id']) > 32){
				returnJSON(array('t' => 'خطأ','m' => 'محاولة جيدة، نرجو عدم تكرارها','tp' => 'error','b' => 'موافق'));		
			}


			$conn=$database->openConnection();
			$check=$conn->prepare('SELECT Credits FROM Customers WHERE id=:id');
			$check->bindParam(":id", $_SESSION['memberId:light'], PDO::PARAM_INT);
			$check->execute();


			$check2=$conn->prepare('SELECT name,price,file,id FROM products WHERE id=:idd');
			$check2->bindParam(":idd", $_POST['id'], PDO::PARAM_INT);
			$check2->execute();
			
			$check3=$conn->prepare('SELECT id FROM owners WHERE id_cl=:clid AND id_pr =:ipr');
			$check3->bindParam(":clid", $_SESSION['memberId:light'], PDO::PARAM_INT);
						$check3->bindParam(":ipr", $_POST['id'], PDO::PARAM_INT);

			$check3->execute();

if($check3->rowCount() > 0 ){
    returnJSON(array('t'=>'خطأ','m'=>'انت تملك هذا الملف ب الفعل', 's'=>'error', 'b'=>'موافق'));
}

			if($check->rowCount() > 0 and $check2->rowCount() > 0){

	$client =$check->fetch(PDO::FETCH_ASSOC);

	$product =$check2->fetch(PDO::FETCH_ASSOC);
$file = $product['file'];
$pr_id = (int)$product['id'];
$pr_name = $product['name'];

if($client["Credits"] >= $product['price']) {

        $removeCredits = $conn->prepare('UPDATE Customers SET Credits= Credits - '.$product['price'].' 
          WHERE id=:iddd
                ');
        $removeCredits->bindValue(':iddd', (int)$_SESSION['memberId:light'],  PDO::PARAM_INT);
        $removeCredits->execute();        
        $removeCredits->CloseCursor();


	if($removeCredits->rowCount() > 0){
$randcode = rand_code(25);
        $stmt= $conn->prepare('INSERT INTO owners ( pr_name,id_pr, id_cl, file,create_time,code) 
                             VALUES (:nm, :pr, :cl, :f, '.time().',:cd)
                           ');
        $stmt->bindValue(':nm', $pr_name, PDO::PARAM_STR);
        $stmt->bindValue(':pr', $pr_id, PDO::PARAM_INT);
        $stmt->bindValue(':cl', (int)$_SESSION['memberId:light'], PDO::PARAM_INT);
        $stmt->bindValue(':f', $file, PDO::PARAM_STR);
        $stmt->bindValue(':cd', $randcode, PDO::PARAM_STR);



        $stmt->execute();        
        $stmt->CloseCursor();
        

         returnJSON(array('t' => 'تم','m' => 'تمت عملية الشراء بنجاح ! تهانينا.','tp' => 'success', 'b' => true));



}else{
    
        
		returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة', 's'=>'error', 'b'=>'موافق'));

}


}else{
    
    returnJSON(array('t'=>'خطأ','m'=>'لا يوجد لديك رصيد كافي', 's'=>'error', 'b'=>'موافق'));
    
}
}else{
    
		returnJSON(array('t'=>'خطأ','m'=>'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة', 's'=>'error', 'b'=>'موافق'));

}

}
?>