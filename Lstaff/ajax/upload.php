<?php
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");

if(isset($_FILES['file'],$_SESSION['staffId:light'])){
    $UploadDirectory    = '../../Lstorage/';

		if(!isset($_SESSION['_token'])){
		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 'tp'=>'error'));
	}

	if(antiSpam("upload:upload.php")){
		returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 'tp'=>'error', 'b'=>'موافق'));
	}

    
    		
if(!isset($_FILES["file"])
    || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
returnJSON(array('t'=>'خطأ', 'm'=>''.$_FILES["file"]["error"].' .', 'tp'=>'error'));}

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
   		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 'tp'=>'error'));

}

if ($_FILES["file"]["size"] > 29000000) {
returnJSON(array('t'=>'خطأ', 'm'=>'حجم الملف كبير جدا.', 'tp'=>'error'));}



    //allowed file type Server side check
    $allowedTypes = array('rar', 'zip', 'tgz');

    $File_Name          = strtolower($_FILES['file']['name']);
    $File_Ext           = pathinfo($File_Name, PATHINFO_EXTENSION);
    if(!in_array($File_Ext, $allowedTypes)) {
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'نوع الملف غير مدعوم ','b' => true));
              }
    $NewFileName        = $File_Name; //new file name
                                       
                             
$filename = $UploadDirectory.$NewFileName;

	if(file_exists($filename)){
			returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'يوجد منتج بنفس الإسم ','b' => true));
          }



    if(move_uploaded_file($_FILES['file']['tmp_name'], $UploadDirectory.$NewFileName ))
       {
 returnJSON(array('tp' => 'success', 't' => 'تم', 'm' => ' اكتملت العملية بنجاح','b' => true));
 }else{
returnJSON(array('t'=>'خطأ', 'm'=>'فشلت العملية.', 'tp'=>'error'));    }
    
    
    
    }
	


?>