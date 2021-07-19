<?php
$amstaff = true;
require_once("../../inc/db.php");
require_once("../../inc/functions.php");
require_once("../inc/protection.php");
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
		returnJSON(array('t'=>'خطأ', 'm'=>'حدث خطأ غير متوقع ، رجاءً قم بتحديث الصفحة.', 's'=>'error'));
	}
	
	if(isset($_POST['closeTicket'],$_SESSION['staffId:light'])){
		if(!ctype_digit($_POST['closeTicket']) OR mb_strlen($_POST['closeTicket']) > 50){
			returnJSON(array('t' => 'عفواً','m' => 'تأكد من المدخلات!','tp' => 'error','refresh' => false));	
		} else if(antiSpam('ticketStaff:close')){
			returnJSON(array('t' => 'خطأ من فضلك أنتظر قليلا بين محاولاتك','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		} else{
			$conn = $database->openConnection();
			$check = $conn->query('SELECT status,for_rank FROM ticket WHERE id = '.$_POST['closeTicket'].'');
			if($check->rowCount() > 0){
				$data = $check->fetch();
				if($data['status'] != 0){
					if($data['for_rank'] == 5 OR $data['type'] == 5){
						$getRank = current($conn->query('SELECT rank FROM Customers WHERE id = '.$_SESSION['staffId:light'].'')->fetch());
						if($getRank != 5){
							returnJSON(array('t'=>'عذرًا، لا تمتلك صلاحية لإغلاق التذكرة', 'tp'=>'error'));
						}
					}
					$updateStatus = $conn->query('UPDATE ticket SET status = 0 WHERE id = '.$_POST['closeTicket'].'');
					if($updateStatus->rowCount() > 0){
						returnJSON(array('t'=>'حسناً تم اغلاق التذكرة بنجاح!', 'tp'=>'success', 'done'=>true));
					}
				} else {
					returnJSON(array('t'=>'خطأ التذكرة مغلقة فعلاً!','tp'=>'error'));
				}
			}else{
				returnJSON(array('t' => 'حدث خطأ أثناء العملية, سيتم اعادة تحميل الصفحة.','tp' => 'error','refresh' => true));	
			}
		}
	}
	
	if(isset($_POST['allowtoreply'], $_SESSION['staffId:light'])) {
		if(!ctype_digit($_POST['allowtoreply'])) {
			returnJSON(array('t'=>'حدث خطأ اثناء تنفيذ العملية.. حاول مججداً!','tp'=>'error'));
		}  else if(antiSpam('ticketStaff:reply')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		}
		$conn = $database->openConnection();
		$stmt = $conn->query('SELECT rank FROM Customers WHERE id = '.$_SESSION['staffId:light'].'');
		if($stmt->rowCount() > 0){
			$rank = $stmt->fetch()['rank'];
			if($rank != 5){
				returnJSON(array('t'=>'عذرًا، لاتمتلك صلاحية للسماح للوسيط بالرد.', 'tp'=>'error'));
			}
			$getForWho = $conn->query('SELECT forwho FROM ticket WHERE id = '.$_POST['allowtoreply'].' AND status != 0 AND type = 3');
			if($getForWho->rowCount() > 0){
				$forwho= $getForWho->fetch()['forwho'];
				$update = $conn->query('UPDATE ticket SET needed_see = '.$forwho.' WHERE id = '.$_POST['allowtoreply'].'');
				if($update->rowCount() > 0){
					returnJSON(array('t'=>'تم السماح للوسيط بإضافة رد.', 'tp'=>'success'));
				} else {
					returnJSON(array('t'=>'عذرًا ، مسموح فعلاً للوسيط بإضافة رد.', 'tp'=>'error'));
				}
			} else {
				returnJSON(array('t'=>'لم يتم العثور على التذكرة', 'tp'=>'error'));
			}
			
		}
	}
	

	if(isset($_POST['checkreply'],$_POST['text'], $_SESSION['staffId:light'])) { /* 0 => Closed, 1 => Opened, 2 => Admin Reply , 3 => Customer Reply */
		if(!ctype_digit($_POST['checkreply'])) { exit("NONONO"); }else if(antiSpam('ticketStaff:checkreply')){
			returnJSON(array('t' => 'خطأ','m' => 'من فضلك أنتظر قليلا بين محاولاتك','tp' => 'error','b' => 'موافق'));		
		} 
		$conn = $database->openConnection();
		$check = $conn->query('SELECT id,status,creator,for_rank,needed_see FROM ticket WHERE id = '.$_POST['checkreply']);
		if($check->rowCount() > 0){
			$data = $check->fetch();
			if($data['status'] == 0) {
				http_response_code(997);
				returnJSON(array('done'=>true, 't' => 'خطأ','m' => 'التذكرة مغلقة','tp' => 'error'));
			}else if($data['status'] != 0){
				if($data['for_rank'] == 5 && $data['needed_see'] != $_SESSION['staffId:light'] OR $data['type'] == 5){
					$getRank = current($conn->query('SELECT rank FROM Customers WHERE id = '.$_SESSION['staffId:light'].'')->fetch());
					if($getRank != 5){
						returnJSON(array('t'=>'عذرًا، لا تمتلك صلاحية لإضافة رد', 'tp'=>'error'));
					}
				}
				$update = $conn->query('UPDATE ticket SET status = 2 WHERE id = '.$data['id'].'');
				$insert = $conn->prepare('INSERT INTO ticket_replies (tid, reply,replyfrom,reply_time,from_rank) VALUES ('.$data['id'].', :reply, '.$_SESSION['staffId:light'].', '.time().', 0)');
				$insert->bindParam(":reply", $_POST['text']);
				$insert->execute();
				addNotify('تم إضافة رد على تذكرتك! راجع التذكرة', $data['creator'] );
					
				if($insert->rowCount() > 0){
					$getReplyData = $conn->query('SELECT username,img FROM Customers WHERE id='.$data['creator'])->fetch();
					if($getReplyData['img'] == "") { $img = 'https://i.imgur.com/Dj6PUOi.png'; } else { $img = $getReplyData['img']; }
					returnJSON(array('done'=>true, 't' => 'حسناً تم إضافة الرد بنجاح','tp' => 'success', 'data'=>'
					                    <tr class="table-active">
                        <td class="font-size-sm text-muted" colspan="2" style="text-align: left !important;">
                           منذ <em>الآن</em> » <a href="#"> '.htmlspecialchars($getReplyData['username']).'</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>'.htmlspecialchars($_POST['text']).'</p>
						</td>
						<td class="d-none d-sm-table-cell text-center" style="width: 140px;">
                            <div class="mb-10">
                                <a href="#">
                                     <img class="img-avatar" src="'.$img.'" alt="">
                                </a>
                            </div>
                           <small>'.htmlspecialchars($getReplyData['username']).'<br>'.getRank($data['creator']).'</small>
                        </td>
                    </tr>
					'));
				}
				
			}
		}
	}



}
?>