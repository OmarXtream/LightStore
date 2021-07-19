<?php
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");
if($_SERVER['REQUEST_METHOD'] == "GET"){
    
	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token']){
		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 'tp'=>'error'));
	}
	if(isset($_GET['q'],$_SESSION['staffId:light'])){
					if(antiSpam('Sshowpr:Sshowpr.php')){
            echo' من فضلك إنتظر بين محاولاتك';					    
				die;
				
				
			} else if(!rankPermission($_SESSION['staffId:light'],5,true) OR rankPermission($_SESSION['staffId:light'],1,true)){
			    				echo"لا تمتلك صلاحيات كافيه";
die;
				
				
			} else	if(!ctype_digit($_GET['q']) OR strlen($_GET['q']) > 32){
			    			echo' محاولة جيده , لا تكررها ';
          
die;
			}

$prid = (int)$_GET['q'];
$db = $database->openConnection();

    $response = $db->prepare('SELECT pr_name,pr_type,pr_des,pr_price
            FROM Sproducts
            WHERE id = :nom 
           ');
    $response->bindValue(':nom',$prid,PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetch();
    $response->CloseCursor();
if($member){
$id = $prid;
$name = htmlspecialchars($member['pr_name']);
$type = (int)$member['pr_type'];
$des = htmlspecialchars($member['pr_des']);
$price = (int)$member['pr_price'];
echo'
                       <form  method="post" onsubmit="return false;">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary ">
                                    <input type="text" class="form-control" id="p_name" name="p_name" value="'.$name.'">
                                    <label for="material-color-primary2">إسم المنتج</label>
                                </div>
                                </div>

                            </div>

                                <div class="form-material">
                                    <textarea class="form-control" id="p_des" name="p_des" rows="3">'.$des.'</textarea>
                                    <label for="material-textarea-small2">وصف مختصر للمنتج</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-sm ">
                                    <input type="number" class="form-control form-control-sm" id="p_price" name="p_price" value="'.$price.'">
                                    <label for="material-input-size-sm2"> السعر</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material ">
                                    <select class="form-control" id="p_type" name="p_type">
                                        <option></option>';
                                        
                
                                        
                                        
                                        
                                         if($type == 1){ 
echo'<option value="1" selected> سيارات </option>'; 
}else{
echo'<option value="1"> سيارات </option>'; }
 if($type == 2){ 
 echo'<option value="2" selected> سكربتات</option>'; 
 }else{
	 echo'<option value="2"> سكربتات</option>'; 
	 }
 if($type == 3){ 
 echo'<option value="3" selected>مهمات  </option>'; 
 }else{
	 echo'<option value="3">مهمات</option>'; 
	 }
	  if($type == 4){ 
 echo'<option value="4" selected>أخرى  </option>'; 
 }else{
	 echo'<option value="4">أخرى</option>'; 
	 }

 
 echo'

                                    </select> <label for="material-select2">إختر قسم المنتج</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9">
<center>
    <button onClick="_predit('.$id.')" type="submit" class="btn btn-alt-info"> تعديل المنتج <i class="si si-pencil"></i> </button>

        <button type="submit" onClick="_prdel('.$id.')" class="btn btn-alt-danger"> حذف المنتج <i class="si si-close"></i> </button>

</center>
';





}else{
echo 'لم يتم العثور على المنتج';
}
}
}
 ?>