<?php
$pageName = "ticket";
ob_start();
require_once("inc/header.php");

if(isset($_GET['view'])) {
	if(!ctype_digit($_GET['view'])) { exit(header('Location: ./ticket')); }
	$conn = $database->openConnection();
	$stmt = $conn->query('SELECT t.id,t.creator,t.title,t.msg,t.type,t.status,t.time,tr.reply ticketReply,tr.reply_time reply_time, tr.replyfrom, tr.from_rank FROM ticket t LEFT JOIN ticket_replies tr ON t.id = '.$_GET['view'].' AND tr.tid = '.$_GET['view'].' AND t.id = '.$_GET['view'].' WHERE t.id = '.$_GET['view'].' AND t.status != 0 ORDER BY tr.id DESC');
	if($stmt->rowCount() > 0){
		$data = $stmt->fetchAll();
		$dataC = $conn->query('SELECT username,img,id FROM Customers WHERE id = '.$data[0]['creator'].'')->fetch();
		$img = $dataC['img'] ?: 'https://i.imgur.com/Dj6PUOi.png';
		$last_key =array_key_last($data);
		$chat='';
		$text='';
		foreach($data as $da){
			if($da['reply_time'] === null){continue;}
			$dataR = $conn->query('SELECT username,img FROM Customers WHERE id = '.$da['replyfrom'].'')->fetch();
			$imgR = $dataR['img'] ?: 'https://i.imgur.com/Dj6PUOi.png';
			$chat .= '<tr class="table-active">
                        <td class="font-size-sm text-muted" colspan="2" style="text-align: left !important;">
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

		echo '<div class="content">
    <h2 class="content-heading" style="padding-bottom: 10px;">
    </h2><div class="row"><div class="col-md-2"></div><div class="col-md-8" id="TicketV"><div class="block">
<div class="block-header block-header-default">
<div class="block-title text-right" id="ticketName">'.htmlspecialchars($data[$last_key]['title']).'</div>
<div class="block-options" id="ticketbuttons"><a class="btn-block-option js-scroll-to-enabled  pl-4 pr-4" data-toggle="scroll-to" onclick="replyticket('.$da['id'].')">رد <i class="fa fa-reply"></i></a><button onclick="doThisTicket('.$da['id'].')" type="button" class="btn-block-option pl-4 pr-4" data-dismiss="modal" aria-label="Close"> اغلاق التذكرة <i class="si si-close"></i> </button><button onclick="_backTicketStaff()" type="button" class="btn-block-option pr-4" data-dismiss="modal"> الرجوع للخلف <i class="fa fa-arrow-left"></i></button>'.$text.'</div>
</div>
<div class="block-content p-5" id="ticketInfo" data-toggle="slimscroll" data-height="350px">
			<table class="table table-borderless">
                <tbody id="datastaffticket">
					'.$chat.'
                    <tr class="table-active">
                        <td class="font-size-sm text-muted" colspan="2" style="text-align: left !important;">
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
                           <small>'.htmlspecialchars($dataC['username']).'<br>'.getRank($dataC['id']).'</small>
                        </td>
                    </tr>
					
                   
                </tbody>
            </table></div>
</div></div></div></div>';
	} else {
		exit(header('Location: ./ticket'));
	}
} else {
$conn = $database->openConnection();
$alltickets = $conn->query("SELECT * FROM ticket WHERE status != 0 ORDER BY time DESC");
ob_end_flush();
 ?>
 <link rel="stylesheet" href="assets/js/plugins/datatables/dataTables.bootstrap4.min.css">
<main id="main-container">

                <!-- Page Content -->
                <div class="content">
                    <h2 class="content-heading">التذاكر &mdash; الإدارة</h2>

                    <!-- Dynamic Table Full -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">جميع التذاكر » <small>المفتوحة</small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" data-order='[[ 1, "desc" ]]' dir="ltr">
                                <thead>
                                    <tr>
                                       
                                        <th class="d-none d-sm-table-cell text-center">منذ</th>
										<th class="d-none d-sm-table-cell text-center">الحالة</th>
										<th class="text-center">النوع</th>
										<th >العنوان</th>
										<th class="d-none d-sm-table-cell" style="width: 100px;" >#</th>
                                    </tr>
                                </thead>
                                <tbody>
							<?php foreach($alltickets as $row){
								$id = sprintf("%06d", $row['id']);
								if($row['type'] == 1){$type = 'إستفسار';}elseif($row['type'] == 2){$type = 'إقتراح';}elseif($row['type'] == 4){$type = 'مشكلة';}else{$type='none';}
								if($row['status'] == 1){$status = 'مفتوحة';}elseif($row['status'] == 2){$status = 'رد الدعم';}elseif($row['status'] == 3){$status = 'رد العميل';}elseif($row['status'] == 0){$status = 'مغلقة';}else{$status='none';}
							?>
                                <tr> 
									<td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><i class="fa fa-clock-o"></i> <?=ago($row['time'])?></a>
                                    </td>
									<td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><?=$status?></a>
                                    </td>
									<td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><?=$type?></a>
                                    </td>
									<td>
                                        <a class="text-gray-dark" href="javascript:void(0)"><?=htmlspecialchars($row['title']); ?></a>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <a class="text-gray-dark" href="?view=<?=$row['id']?>"><?=$id?></a>
                                    </td>

                                </tr>
							<?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->
                    

                  
                </div>
                <!-- END Page Content -->

            </main>
<?php } ?>
<script>
var token=document.getElementsByTagName('meta')["token"].content;
var xmlhttp;

if(window.XMLHttpRequest){
	xmlhttp = new XMLHttpRequest();
} else {
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}


function _backTicketStaff(){
	window.location = "ticket";
}


function closeticket(tid){
	sendData("ticket", "closeTicket="+tid,'POST')
		.then(function(response){
			toast({
			  type: response.tp,
			  title: response.t
			});
	});
}


function replyticket(tid){
	swal({
		title: 'قم بإدخال الرد من فضلك',
		type: 'question',
		input: 'text',
		inputPlaceholder: 'شكرًا لكم',
		showCancelButton: true,
		confirmButtonText: 'إرسال',
		cancelButtonText: 'إلغاء الأمر',
		showLoaderOnConfirm: true,
		inputValidator: (value) => {
			return !value && 'من فضلك ادخل الرد'
		},
		preConfirm: (text) => {
			return fetch(`ajax/ticket`, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				redirect: "follow",
				body: "checkreply="+tid+"&text="+text+"&token="+document.getElementsByTagName('meta')["token"].content
			}).then(response => {
				if (response.status === 998) {
				  Swal.showValidationError("يجب إن تستقبل رد من الدعم الفني.")
				} else if(response.status == 997){
				  Swal.showValidationError("التذكرة مغلقة, لا تستطيع الرد")
				}
				return response.json()
			  });
		 },
		inputAttributes: {
			maxlength: 32,
			autocapitalize: 'off',
			autocorrect: 'off'
		},
		allowOutsideClick: () => !swal.isLoading()
	}).then((result) => {
		if(typeof result.value.updatetoken != 'undefined'){
			document.getElementsByTagName('meta')["token"].content = result.value.updatetoken;
		}
		if(result.value.done){
			toast({
			  type: result.value.tp,
			  title: result.value.t
			});
			var data = document.getElementById('datastaffticket').innerHTML;
			document.getElementById('datastaffticket').innerHTML = result.value.data + data;
		} else {
			toast({
			  type: result.value.tp,
			  title: result.value.t
			});		
		}
	});
}
 
function doThisTicket(tid){
	_sure('هل انت متاكد من انك تريد غلق التذكرة ؟','info',closeticket,tid,'');
}
function _sure(question,qicon,runthis,thisarg,twoarg){
			
		swal({
			title: "هل انت متاكد؟",
			text: question,
			type: qicon,
			showCancelButton: true,
			cancelButtonColor: "#ef5350",
			confirmButtonText: "نعم",
			cancelButtonText: "لأ"
			})
			.then((rep) => {
				if (rep) {
					if(rep.dismiss !== 'overlay' && rep.dismiss !== 'esc'){
						if(rep.value == true){
							if(typeof thisarg != 'undefined' && typeof twoarg == 'undefined'){
							runthis(thisarg); // _sure("ثفثقف","info",wseet,"info",75);
							}else{
							runthis(thisarg,twoarg);
							}	
						}else{
							
						}
				}
				}
				
			});
			
			
}
</script>
<?php require_once("inc/footer.php"); ?>