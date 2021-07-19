<?php
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");
if($_SERVER['REQUEST_METHOD'] == "GET"){
    
	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token']){
echo'حدث خطا الرجاء قم بتحديث الصفحة';
die;
}
	if(isset($_GET['q'],$_SESSION['staffId:light'])){
					if(antiSpam('unup:unup.php')){
            echo' من فضلك إنتظر بين محاولاتك';					    
				die;
				
				
			} else if(!rankPermission($_SESSION['staffId:light'],5,true) OR rankPermission($_SESSION['staffId:light'],1,true)){
			    				echo"لا تمتلك صلاحيات كافيه";
die;
				
				
}


	try{
	$stmt = $conn->prepare('DELETE FROM products WHERE file = :ff');
	$stmt->bindValue(":ff", $_GET['q']);
    $stmt->execute();
    $stmt->CloseCursor(); 
	}
    catch (PDOException $e)
    {
    }
	try{
		$stmt2 = $conn->prepare('DELETE FROM owners WHERE file = :fff');
	$stmt2->bindValue(":fff", $_GET['q']);
    $stmt2->execute();
    $stmt2->CloseCursor(); 
    }
    catch (PDOException $e)
    {
    }

	
	       $UploadDirectory    = '../../Lstorage/';
       $fileName = $_GET['q'];

    $filePath = $UploadDirectory.$fileName;

    if(!empty($fileName) && file_exists($filePath)){

secure_delete($filePath);

echo'تم حذف الملف بنجاح';
    }else{
        
echo'لم يتم العثور على الملف';
}




}
}
 

?>