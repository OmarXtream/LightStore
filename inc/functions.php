<?php

if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

function secure_delete($file_path)
{
    $file_size = filesize($file_path);
    $new_content = str_repeat('0', $file_size);
    file_put_contents($file_path, $new_content);
    unlink($file_path);
    return true;

}

function rand_code($len)
{
 $min_lenght= 0;
 $max_lenght = 100;
 $bigL = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
 $smallL = "abcdefghijklmnopqrstuvwxyz";
 $number = "0123456789";
 $bigB = str_shuffle($bigL);
 $smallS = str_shuffle($smallL);
 $numberS = str_shuffle($number);
 $subA = substr($bigB,0,5);
 $subB = substr($bigB,6,5);
 $subC = substr($bigB,10,5);
 $subD = substr($smallS,0,5);
 $subE = substr($smallS,6,5);
 $subF = substr($smallS,10,5);
 $subG = substr($numberS,0,5);
 $subH = substr($numberS,6,5);
 $subI = substr($numberS,10,5);
 $RandCode1 = str_shuffle($subA.$subD.$subB.$subF.$subC.$subE);
 $RandCode2 = str_shuffle($RandCode1);
 $RandCode = $RandCode1.$RandCode2;
 if ($len>$min_lenght && $len<$max_lenght)
 {
 $CodeEX = substr($RandCode,0,$len);
 }
 else
 {
 $CodeEX = $RandCode;
 }
 return $CodeEX;
}

function Ptype(int $type){
switch ($type) {
    case '1':
        $tr = "سيارات";
        break;
    case '2':
        $tr = "سكربتات";
        break;
    case '3':
        $tr = "مهمات";
        break;
       case '4':
        $tr = "أخرى";
        break;

    default:
 $tr = "أخرى";
    
}
return $tr;
}

function antiSpam(string $sessionName, int $timeInSec = 3){
	if(isset($_SESSION[$sessionName])){
		if($_SESSION[$sessionName] > time()){
			return true;
		}else{
			$_SESSION[$sessionName] = time() + $timeInSec;
			return false;
		}
	}else{
			$_SESSION[$sessionName] = time() + $timeInSec;
	}
}

function getRank(int $id){
	global $database;

	$a = '';
	
	$conn=$database->openConnection();
	
	$stmt=$conn->query("SELECT rank FROM Customers WHERE id={$id}");
	
	if($stmt->rowCount() !== 0){
		
		$ranks=array(0 => array('Member' => 'PRIMARY'), 1 => array('Technical Support' => 'SUCCESS'), 2 => array('SuperVisor' => 'SUCCESS'), 3 => array(' Director' => 'DANGER'), 4 => array('Chief services officer' => 'DANGER'), 5 => array('General Manager' => 'DANGER'));
		
		foreach($stmt as $row){
			$rank=$row["rank"];
		}
		
		foreach($ranks as $rs=>$v){
			if($rs == $rank){
				foreach($v as $x=>$a){
					// $x == rank name AND $a == rank color
					
					$a = '<span class="text-'.strtolower($a).'">'.$x.'</span>';
					
				}
			}
		}
	}
		
	$database->closeConnection();
	return $a;
	
}

function rankPermission($id,$staffId,$only = false){
	global $database;
	
	$conn=$database->openConnection();
	
	$stmt=$conn->query("SELECT rank FROM Customers WHERE id={$id}");
	
	if($stmt->rowCount() > 0){
		$rank = current($stmt->fetch());
		return !$only ? $rank >= $staffId ? true : false : $rank == $staffId ? true : false;
		
	} else {
		
		return null;
		
	}
}
function addNotify($notify,int $id){
	global $database;
	
	$conn=$database->openConnection();
	
	$time = time();
	$notifyto = $conn->prepare("INSERT INTO notification (notification,time,cid) VALUES ('{$notify}',{$time},:id)");
	$notifyto->bindValue(":id", $id);
	$notifyto->execute();
}
function validateMobile($phone){
	$phone = str_replace(' ', '', $phone);
	if(!preg_match("/^\+?[0-9]{7,14}$/",$phone)){
		return false;
	}
	return true;
}

function taxCalc(int $price, float $tax, int $type){
	if($type==1){
	return ($tax*$price) + $price;
	}else{
	return $price - ($tax*$price);
	}
}

function tokenHandler(){
	$_SESSION['_token']=bin2hex(random_bytes(16));
	return $_SESSION['_token'];
}

function ago(int $i){
    $m = time()-$i;
	$o='الآن';
    $t = array('سنة'=>31556926,'شهر'=>2629744,'اسبوع'=>604800,
'يوم'=>86400,'ساعات'=>3600,'دقيقه'=>60,'ثانية'=>1);

    foreach($t as $u=>$s){
        if($s<=$m){
			$v=floor($m/$s);
			if($u == "سنة"){
				if($v == 1){
					$o="سنة";
				}else if($v == 2){
					$o="سنتين";
				}else {
					$o="$v $u";
				}
			}else if($u == "شهر"){
				if($v == 1){
					$o="شهر";
				}else if($v == 2){
					$o="شهرين";
				}else {
					$o="$v اشهر";
				}
			}else if($u == "اسبوع"){
				if($v == 1){
					$o = "اسبوع";
				}else if($v == 2){
					$o = "اسبوعين";
				}else if($v >= 3 && $v <= 10){
					$o="$v اسابيع";
				}else{
					$o="$v $u";
				}
			}else if($u == "يوم"){
				if($v == 1){
					$o = "يوم";
				}else if($v == 2){
					$o="يومين";
				}else if($v >= 3 && $v <= 10){
					$o = "$v ايام";
				}else {
					$o = "$v $u";
				}
			}else if($u == "ساعات"){
				if($v == 1){
					$o = "ساعه";
				}else if($v == 2){
					$o="ساعتين";
				}else if($v >= 3 && $v <= 10){
					$o="$v $u";
				}else{
					$o = "$v ساعة";
				}
			}else if($u == "دقيقه"){
				if($v == 1){
					$o="دقيقة";
				}else if($v == 2){
					$o="دقيقتين";
				}else if($v >= 3 && $v <= 10){
					$o="$v دقائق";
				}else{
					$o = "$v $u";
				}
			}else if($u == "ثانية"){
				if($v == 1){
					$o="ثانية";
				}else if($v == 2){
					$o="ثانتين";
				}else if($v >= 3 && $v <= 10){
					$o="$v ثواني";
				}else{
					$o="$v $u";
				}
			}else{}
			
			break;
			//$o="$v $u".($v==1?'':'').''; break;
		}
    }
	
    return $o;
}


function alertprinter($id,$print = true){
	Global $database;
	$db = $database->openConnection();
	$data = $db->query("SELECT * FROM alert_printer WHERE alert_id=$id")->fetch();
	if($print){
		echo"
		<script>
const toast = swal.mixin({
  toast: true,
  position: '{$data['alert_position']}',
  showConfirmButton: false,
  timer: {$data['alert_timer']}
});

toast({
  type: '{$data['alert_type']}',
  title: '{$data['alert_msg']}'
})
</script>
		";
	}else{
		return array('p' => $data['alert_position'],'timer' => $data['alert_timer'],'type' => $data['alert_type'],'msg' => $data['alert_msg']);
	}
	
}

function etc($text,$length){
	if(strlen($text) > $length){
		$text=substr($text,0,$length)."...";
		return $text;
	}else{
		return $text;
	}
}

function returnJSON(array $f,bool $updateToken = true) {
	  /*
		  Usage:
		  returnJSON(array(params));
	  */
	  if(!is_array($f)){ exit; }
	  if($updateToken){
		  $f['updatetoken'] = tokenHandler();
	  }
		  header('Content-Type: application/json');
  
		  exit(json_encode($f));
  
}

function password_strength($password) {
	$returnVal = True;
	if ( !preg_match("#[0-9]+#", $password) ) $returnVal = False;
	if ( !preg_match("#[a-z]+#", $password) ) $returnVal = False;
	if ( !preg_match("#[A-Z]+#", $password) ) $returnVal = False;
	return $returnVal;
}

?>