<?php
require_once("../inc/req.php");
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(!isset($_SESSION['_token']) OR !isset($_POST['token']) OR $_POST['token'] != $_SESSION['_token']){
		returnJSON(array('tp' => 'error', 't' => 'خطأ', 'm' => 'حدث خطأ غير معروف من فضلك أعد تحميل هذه الصفحة','b' => true));
	}
if(isset($_POST['subject'],$_POST['type'],$_POST['text'])){
	$subject = $_POST['subject'];
	$type = $_POST['type'];
	$text = $_POST['text'];
	if(empty($subject) || empty($type) || empty($text) || mb_strlen($subject) > 212 || mb_strlen($text) > 2000 || mb_strlen($type) > 1 || !is_numeric($type) || $type < 1 || $type > 4){
		returnJSON(array('t' => 'خطأ','m' => 'تأكد من المدخلات!','tp' => 'error','b' => true));		
	}else{
		if(antiSpam("open:Ticket.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}
		$conn = $database->openConnection();
		$stmt = $conn->query("SELECT id FROM ticket WHERE creator={$_SESSION['memberId:light']} AND status <> 0");
		if($stmt->rowCount() > 0){
			returnJSON(array('t' => 'خطأ','m' => 'لا يمكنك فتح اكثر من تذكره يجب اغلاق التذكره الأولى','tp' => 'error','b' => true));		
		}else{

				$time = time();
				$insertData = $conn->prepare("INSERT INTO ticket (creator, title, msg, type, status, time) VALUES (:creator,:title,:msg,{$type}, 1, {$time})");
				$insertData->bindValue(':creator', $_SESSION['memberId:light']);
				$insertData->bindValue(':title', $subject);
				$insertData->bindValue(':msg', $text);
				$insertData->execute();
				if($insertData->rowCount() > 0){
					$id= sprintf("%06d", $conn->lastInsertId());
					returnJSON(array('idwithout'=>$conn->lastInsertId(),'id'=>$id,'t' => 'حسناً','m' => 'تم فتح التذكرة بنجاحً!','tp' => 'success','b' => true));	
				}
			
		}
	}
}
if(isset($_POST['read'])){
		if(antiSpam("read:Ticket.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}
	if(!ctype_digit($_POST['read']) || strlen($_POST['read']) > 20){
		returnJSON(array('t' => 'خطأ','m' => 'تأكد من المدخلات!','tp' => 'error','b' => true, 'typy' => 'no'));	
	}else{
		$conn=$database->openConnection();
		$check = $conn->prepare('SELECT t.id,t.creator,t.title,t.msg,t.type,t.status,t.time,tr.reply ticketReply,tr.reply_time reply_time, tr.replyfrom, tr.from_rank FROM ticket t LEFT JOIN ticket_replies tr ON t.id = :id AND tr.tid = :id WHERE t.creator = '.$_SESSION['memberId:light'].' AND t.id = :id ORDER BY tr.id DESC');
		$check->bindValue(":id", $_POST['read'], PDO::PARAM_INT);
		$check->execute();
		if($check->rowCount() > 0){
			$dataC = $conn->query('SELECT username,img FROM Customers WHERE id = '.$_SESSION['memberId:light'].'')->fetch();
			$img = $dataC['img'] ?: 'https://i.imgur.com/Dj6PUOi.png';
			$chat = '';
			$data = $check->fetchAll();
			$last_key =array_key_last($data);
			$ttlreplies= 1;
			foreach($data as $da){
				if($da['reply_time'] === null){continue;}
				$dataR = $conn->query('SELECT username,img FROM Customers WHERE id = '.$da['replyfrom'].'')->fetch();
				$imgR = $dataR['img'] ?: 'https://i.imgur.com/Dj6PUOi.png';
				$chat .= '<tr class="table-active">
                        <td class="font-size-sm text-muted" colspan="2">
                            منذ <em>'.ago($da['reply_time']).'</em> » <a href="#"> '.htmlspecialchars($dataR['username']).'</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>'.htmlspecialchars($da['ticketReply']).'</p>
						</td>
						<td class="d-none d-sm-table-cell text-center" style="width: 140px;">
                            <div class="mb-10">
                                <a href="#">
                                    <img class="img-avatar" src="'.$imgR.'" alt="">
                                </a>
                            </div>
                            <small>'.htmlspecialchars($dataR['username']).'<br>'.getRank($da['creator']).'</small>
                        </td>
                    </tr>';
			}
			$arr= array('show'=>true, 'title'=>htmlspecialchars($data[$last_key]['title']),'id'=>$data[$last_key]['id'], 'text'=>'
			<table class="table table-borderless">
                <tbody>
					'.$chat.'
                    <tr class="table-active">
                        <td class="font-size-sm text-muted" colspan="2">
                            منذ <em>'.ago($data[$last_key]['time']).'</em> » <a href="#"> '.htmlspecialchars($dataC['username']).'</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>'.htmlspecialchars($data[$last_key]['msg']).'</p>
						</td>
						<td class="d-none d-sm-table-cell text-center" style="width: 140px;">
                            <div class="mb-10">
                                <a href="#">
                                    <img class="img-avatar" src="'.$img.'" alt="">
                                </a>
                            </div>
                            <small>'.htmlspecialchars($dataC['username']).'<br>'.getRank($_SESSION['memberId:light']).'</small>
                        </td>
                    </tr>
					
                   
                </tbody>
            </table>');
			returnJSON($arr);
		} else {
			returnJSON(array('t' => 'خطأ','m' => 'لم يتم العثور على التذكرة..','tp' => 'danger'));
		}
	}

}

if(isset($_POST['checkreply'])) {
	if(antiSpam("reply:Ticket.php")){
		http_response_code(999);
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'), false);
	} else if(mb_strlen($_POST['checkreply']) > 64){
		http_response_code(995);
		returnJSON(array('t'=>'خطأ','m'=>'يجب الا تتعدى الاحرف 64 حرف.', 's'=>'error', 'b'=>'موافق'), false);
	}
	$conn = $database->openConnection();
	$check = $conn->query('SELECT id,status FROM ticket WHERE creator = '.$_SESSION['memberId:light']);
	if($check->rowCount() > 0){
		$data = $check->fetch();
		if($data['status'] == 2){
			$update = $conn->query('UPDATE ticket SET status = 3 WHERE id = '.$data['id'].'');
			$insert = $conn->prepare('INSERT INTO ticket_replies (tid, reply,replyfrom,reply_time,from_rank) VALUES ('.$data['id'].', :reply, '.$_SESSION['memberId:light'].', '.time().', 0)');
			$insert->bindParam(":reply", $_POST['checkreply']);
			$insert->execute();
			
			if($insert->rowCount() > 0 && $update->rowCount() > 0){
				returnJSON(array('done'=>true, 't' => 'حسناً','m' => 'تم إضافة الرد بنجاح','tp' => 'success'));
			}
		} else if($data['status'] == 0) {
			http_response_code(997);
			returnJSON(array('error'=>'Ticket Closed'), false);

		}else{
			http_response_code(998);
			returnJSON(array('t' => 'عفواً','m' => 'يجب إن تستقبل رد من الدعم الفني.','tp' => 'warning'), false);
		}
	} else{
		returnJSON(array('error'=>'What are you trying to do?'));
	}
}

if(isset($_POST['updatestatus'])){
	if(antiSpam("updateStatus:ticket")){
		returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
	}
	$conn = $database->openConnection();
	$countStatus= $conn->query('Select (select count(*) from ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 1 ) as OpenedCount, (select count(*) from ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 2) as replySupport, (select count(*) FROM ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 3) as customerReply, (SELECT count(*) FROM ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 0) as closedCount');
	if($countStatus->rowCount() > 0){
		$data = $countStatus->fetch();
		returnJSON(array('done'=>true,
		'totaltickets'=> $countStatus->rowCount(),
		'totalOpened' => $data['OpenedCount'],
		'supportReply' => $data['replySupport'],
		'totalCustomer' => $data['customerReply'],
		'totalClosed' => $data['closedCount']));
	} else {
		returnJSON(array('done'=>true,'totaltickets'=> 0,
		'totalOpened' => 0,
		'supportReply' => 0,
		'totalCustomer' => 0,
		'totalClosed' => 0));
	}
}

if(isset($_POST['closeTicket'])){
		if(antiSpam("close:Ticket.php")){
			returnJSON(array('t'=>'خطأ','m'=>'من فضلك انتظر قليلا ثم حاول مجدداً', 's'=>'error', 'b'=>'موافق'));
		}
	if(!ctype_digit($_POST['closeTicket']) OR mb_strlen($_POST['closeTicket']) > 50){
		returnJSON(array('t' => 'عفواً','m' => 'تأكد من المدخلات!','tp' => 'danger','refresh' => false));	
	}else{
		$conn = $database->openConnection();
		$check = $conn->query('SELECT status FROM ticket WHERE id = '.$_POST['closeTicket'].' AND creator = '.$_SESSION['memberId:light']);
		if($check->rowCount() > 0){
			$data = $check->fetch();
			if($data['status'] != 0){
				$updateStatus = $conn->query('UPDATE ticket SET status = 0 WHERE id = '.$_POST['closeTicket'].'');
				if($updateStatus->rowCount() > 0){
					returnJSON(array('t'=>'حسنأً','m'=>'تم اغلاق التذكرة بنجاح!','tp'=>'success', 'done'=>true));
				}
			} else {
				returnJSON(array('t'=>'خطأ','m'=>'التذكرة مغلقة فعلاً!','tp'=>'danger'));
			}
			/* 0 => Closed, 1 => Opened, 2 => Admin Reply , 3 => Customer Reply */
		}else{
			returnJSON(array('t' => 'خطأ','m' => 'حدث خطأ أثناء العملية, سيتم اعادة تحميل الصفحة.','tp' => 'danger','refresh' => true));	
		}
	}
}
}
?>