<?php
if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require 'req.php';
ini_set('display_errors', 1);
$conn=$database->openConnection();
$sql = $conn->query("SELECT username,email,img,rank,phonenumber,Credits FROM Customers WHERE id='{$_SESSION['memberId:light']}'");
if($sql->rowCount() > 0){
	$row = $sql->fetch();
		$clientnickname = htmlspecialchars($row['username']);
		$clientemail = $row['email'];
		$clientimage = $row['img'];
		$clientPhoneNumber = $row['phonenumber'];
		$clientrank = $row['rank'];
		$clientbalance = (int)$row['Credits'];
		$clientImage = $row['img'];

	if($clientimage == ""){
	$clientimage = 'https://png.icons8.com/cotton/64/000000/gender-neutral-user.png';
	}

} else {
	exit(header("Refresh:0; url=logout.php"));
}
$database->closeConnection();


?>
