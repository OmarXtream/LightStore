<?php

if($_SERVER['REQUEST_METHOD'] == "GET"){
require_once("inc/db.php");
session_start();
require_once("inc/config.php");
require_once("inc/functions.php");

    $conn=$database->openConnection();
$sql = $conn->query("SELECT username,email,img,rank,phonenumber,Credits FROM Customers WHERE id='{$_SESSION['memberId:light']}'");
if($sql->rowCount() > 0){
	$row = $sql->fetch();
		$clientnickname = htmlspecialchars($row['username']);
		$clientemail = $row['email'];
		$clientimage = $row['img'];
		$clientPhoneNumber = $row['phonenumber'];
		$clientrank = $row['rank'];
		$clientbalance = $row['Credits'];
		$clientImage = $row['img'];

	if($clientimage == ""){
	$clientimage = 'https://png.icons8.com/cotton/64/000000/gender-neutral-user.png';
	}

} else {
	exit(header("Refresh:0; url=logout.php"));
}
$database->closeConnection();


    
 	if(!isset($_SESSION['_token']) OR !isset($_GET['token']) OR $_GET['token'] != $_SESSION['_token']){
		 die;
	}
   
    
if(isset($_GET['code'],$_GET['token'])){
    			if(antiSpam('download:download.php',60)){
    			    echo'stop spam please ^_^';
die;
}
    			$conn=$database->openConnection();


			$check=$conn->prepare('SELECT id,file FROM owners WHERE code=:clid AND id_cl=:cid');
			$check->bindParam(":clid", $_GET['code'], PDO::PARAM_STR);
			$check->bindParam(":cid", $_SESSION['memberId:light'], PDO::PARAM_INT);

			$check->execute();


if($check->rowCount() > 0 ){
			$checked =$check->fetch(PDO::FETCH_ASSOC);

$file = $checked['file'];
$idfile = (int)$checked['id'];

$randcode = rand_code(30);

$data = [
    'cde' => $randcode,
    'ccd' => $_SESSION['memberId:light'],
    'pid' => $idfile,
];
$sql = "UPDATE owners SET code=:cde WHERE id_cl=:ccd AND id =:pid";
$stmtz= $conn->prepare($sql);
$stmtz->execute($data);
$database->closeConnection();



       $UploadDirectory    = 'Lstorage/';
       $fileName = basename($file);
$ext = pathinfo($fileName, PATHINFO_EXTENSION);
$rand = 'Light-'.chr(rand(1,122));

       $new_filename = "".$rand.".".$ext."";

    $filePath = $UploadDirectory.$fileName;
    if(!empty($fileName) && file_exists($filePath)){
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($file));
         header('Content-Disposition: attachment; filename="' . $new_filename . '"');
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        
        // Read the file
        readfile($filePath);
        die;
    }else{
        
echo'error no file';
die;
}

}else{
    die;
}




}
}
        ?>