<?php
include 'inc/phphead.php';


$conn = $database->openConnection();
$sql = $conn->query("SELECT * FROM ticket WHERE creator={$_SESSION['memberId:light']} ORDER BY time DESC");
$countStatus= $conn->query('Select (select count(*) from ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 1 ) as OpenedCount, (select count(*) from ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 2) as replySupport, (select count(*) FROM ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 3) as customerReply, (SELECT count(*) FROM ticket WHERE creator = '.$_SESSION['memberId:light'].' AND status = 0) as closedCount')->fetch();
$pageName ='tickets';
include 'inc/head.php';

?>
    <div class="clients">

		<div class="container">



        <button type="button" class="btn btn-md btn-rounded btn-success float-right" data-toggle="modal" data-target="#modal-compose">إنشاء تذكرة</button>
<h4> <span id="ticketNameTwo">التذاكر : <?=$sql->rowCount()?> </span></h4>        <div class="col-md-5 col-xl-3">
            <div class="js-inbox-nav d-none d-md-block">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title text-right">نوع التذاكر</h3>
                    </div>
                    <div class="block-content">
                        <ul class="nav nav-pills flex-column push text-right">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    <span><i class="fa fa-fw fa-inbox mr-5"></i> مفتوحة</span>
                                    <span class="badge badge-pill badge-secondary" id="totalOpened"><?=$countStatus['OpenedCount']?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    <span><i class="si si-user-following mr-5"></i> رد الدعم</span>
                                    <span class="badge badge-pill badge-secondary" id="supportReply"><?=$countStatus['replySupport']?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    <span><i class="si si-speech mr-5"></i> رد العميل</span>
                                    <span class="badge badge-pill badge-secondary" id="totalCustomer"><?=$countStatus['customerReply']?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    <span><i class="si si-check mr-5"></i> مغلقة</span>
                                    <span class="badge badge-pill badge-secondary" id="totalClosed"><?=$countStatus['closedCount']?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-xl-9" id="TicketV">
            <div class="block">
                <div class="block-header block-header-default">
<div class="block-title text-right" id='ticketName'> التذاكر &mdash; <?=$sql->rowCount()?> </div>
<div class="block-options" id="ticketbuttons">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-rocket"></i></button>
                    </div>
                </div>
                <div class="block-content p-5" id="ticketInfo">
					<table class="table table-striped table-vcenter mb-0" id="ticketData">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">العنوان</th>
                                    <th class="text-center">النوع</th>
                                    <th class=" text-center">الحالة</th>
                                    <th class=" text-center">منذ</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php foreach($sql as $row){
								$id = sprintf("%06d", $row['id']);
								if($row['type'] == 1){$type = 'إستفسار';}elseif($row['type'] == 2){$type = 'إقتراح';}elseif($row['type'] == 3){$type = 'شكوى على شخص';}elseif($row['type'] == 4){$type = 'مشكلة';}elseif($row['type'] == 5){$type = 'طلب أرباح';}else{$type='none';}
								if($row['status'] == 1){$status = 'مفتوحة';}elseif($row['status'] == 2){$status = 'رد الدعم';}elseif($row['status'] == 3){$status = 'رد العميل';}elseif($row['status'] == 0){$status = 'مغلقة';}else{$status='none';}
							?>
                                <tr onclick="readTicket(<?=$row['id'];?>)"> 
                                    <td class="">
                                        <a class="font-w600" href="javascript:void(0)"><?=$id?></a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"><?=htmlspecialchars($row['title']); ?></a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><?=$type?></a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><?=$status?></a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-gray-dark" href="javascript:void(0)"><i class="fa fa-clock-o"></i> <?=ago($row['time'])?></a>
                                    </td>
                                </tr>
							<?php } ?>
                               
                            </tbody>
                        </table>
                </div>
            </div>
        </div> </div>


	<div class="modal fade" id="modal-compose" tabindex="-1">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header">
                    <h3 class="block-title text-right">
                        <i class="fa fa-pencil mr-5"></i> إنشاء تذكرة
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-material form-material-primary input-group">
                                    <input type="email" class="form-control" maxlength="212" id="subject" placeholder="عن ماذا يدور الموضوع؟">
                                    <label>الموضوع</label>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="si si-envelope-open"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-material form-material-primary input-group">
                                    <select class="form-control" id="type">
                                        <option value="0" selected>...</option>
                                        <option value="1">إستفسار</option>
                                        <option value="2">إقتراح</option>
                                        <option value="4">مشكلة</option>
                                    </select>
                                    <label for="material-select">مشكلتك</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-material form-material-primary text-right">
                                    <textarea class="form-control" id="text" rows="6" maxlength="2000" placeholder="اكتب رسالتك.."></textarea>
                                    <label>الرسالة</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">إلغاء</button>
							<button type="button" class="btn btn-alt-primary" onclick="sendTicket()">
                                إنشاء التذكرة <i class="fa fa-send mr-5"></i>
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>







</div></div>  

<script>
var token=document.getElementsByTagName('meta')["token"].content;
var xmlhttp;
var ticketTypes = {1:"إستفسار", 2:"إقتراح",4: "مشكلة"};var ticketsDataTables;

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function sendTicket(){
	$('#modal-compose').modal('hide');
	var subject = document.getElementById("subject").value;
	var type = document.getElementById("type").value;
	var text = document.getElementById("text").value;	
		if(subject.length > 212 || text.length > 2000 || type.length > 1 || subject == '' || text == '' || type == ''){
			swal({
			  title: 'خطأ',
			  text: 'تأكد من المدخلات!',
			  type: 'error',
			  confirmButtonText: 'موافق'
			});
		}else{

				sendData("Ticket.php", "subject="+subject+"&type="+type+"&text="+text)
				.then(function(response){
					swal({
						title: response.t, 
						text: response.m,
						type: response.tp,
						showConfirmButton: response.b,
						confirmButtonText: 'موافق'
					});
					if(response.tp == 'success'){
						updatestatues();
						$('#ticketData').append('<tr onClick="readTicket('+response.idwithout+')"><td class="d-none d-sm-table-cell"><a class="font-w600" href="javascript:void(0)">'+response.id+'</a></td><td><a href="javascript:void(0)">'+escapeHtml(subject)+'</a></td><td class="text-center"> <a class="text-gray-dark" href="javascript:void(0)">'+ticketTypes[type]+'</a></td><td class="text-center"><a class="text-gray-dark" href="javascript:void(0)">مفتوحة</a></td><td class="text-center"><a class="text-gray-dark" href="javascript:void(0)"><i class="fa fa-clock-o"></i> الآن </a></td></tr>');
						subject = '';
						text = '';
					}else if(response.b === null){
						setTimeout(function() {
							location.reload(true);
						}, 3000);
					}
				});
			
		}
}
function readTicket(read, hey = true){
		sendData("Ticket.php", "read="+read)
		.then(function(response){
			if(response.show){
				ticketsDataTables = document.getElementById("ticketInfo").innerHTML;
				if(hey){
					document.getElementById("ticketData").remove();
					document.getElementById("ticketbuttons").innerHTML="<a class='btn-block-option js-scroll-to-enabled  pl-4 pr-4' data-toggle='scroll-to' onClick='replyticket("+read+")'>رد <i class='fa fa-reply'></i></a><button onClick='doThisTicket("+read+")' type='button' class='btn-block-option pl-4 pr-4' data-dismiss='modal' aria-label='Close'> اغلاق التذكرة <i class='si si-close'></i> </button><button onClick='_backTickets()' type='button' class='btn-block-option pr-4' data-dismiss='modal'> الرجوع للخلف <i class='fa fa-arrow-left'></i></button>";
					document.getElementById("ticketName").innerHTML= response.title;
				}
				document.getElementById("ticketInfo").innerHTML= response.text;
				if(hey){
				    
						toast({
					  type: 'sucess',
					  title: 'Request sucessfuly'
					});

				}
			} else 
			{
			    						toast({
					  type: response.tp,
					  title: '<b>'+response.t+'</b>'
					});

		
			}
		});
}

function _backTickets(){
	if(document.getElementById('totalOpened').innerText != 0){
	document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; ['+document.getElementById('totalOpened').innerText+']';
	} else if(document.getElementById('supportReply').innerText != 0){
	document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; ['+document.getElementById('supportReply').innerText+']';
	} else if(document.getElementById('totalCustomer').innerText != 0) {
	document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; ['+document.getElementById('totalCustomer').innerText+']';
	} else if(document.getElementById('totalClosed').innerText != 0){
	document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; ['+document.getElementById('totalClosed').innerText+']';
	} else {
		document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; [0]';
	}
	document.getElementById("ticketbuttons").innerHTML = '<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-rocket"></i></button>';
	document.getElementById("ticketInfo").innerHTML = ticketsDataTables;
}
function doThisTicket(tid){
	_sure('هل انت متاكد من انك تريد غلق التذكرة ؟','info',closeticket,tid,'');
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
			return fetch(`ajax/Ticket`, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				redirect: "follow",
				body: "checkreply="+text+"&token="+document.getElementsByTagName('meta')["token"].content
			}).then(response => {
				if(response.status == 995){
					Swal.showValidationError("يجب الا تتعدى الاحرف 64 حرف.")
				}else if(response.status == 997){
				  Swal.showValidationError("التذكرة مغلقة, لا تستطيع الرد")
				} else if(response.status == 998){
					Swal.showValidationError("يجب إن تستقبل رد من الدعم الفني.")
				} else if(response.status == 999){
					Swal.showValidationError("من فضلك انتظر قليلا ثم حاول مجدداً")
				}
				return response.json()
			  });
		 },
		inputAttributes: {
			maxlength: 64,
			autocapitalize: 'off',
			autocorrect: 'off'
		},
		allowOutsideClick: () => !swal.isLoading()
	}).then((result) => {
		if(typeof result.value.updatetoken != 'undefined'){
			document.getElementsByTagName('meta')["token"].content = result.value.updatetoken;
		}
		if(result.value.done){
			updatestatues();
			readTicket(tid, false);
			$.notify({title: result.value.t,message: result.value.m},{type: result.value.tp});
		}
	});
}
function closeticket(tid){
	sendData("Ticket.php", "closeTicket="+tid)
	.then(function(response){
		$.notify({
			title: '<b>'+response.t+'</b>',
			message: response.m
		},{
			type: response.tp
		});
		if(response.done){
			updatestatues(false);
		}
	});
}

function updatestatues(what = true){
	sendData("Ticket.php", "updatestatus=1")
	.then(function(response){
		if(response.done){
			if(what){
				document.getElementById('ticketName').innerHTML = 'التذاكر &mdash; ['+response.totaltickets+']';
			}
			document.getElementById('ticketNameTwo').innerHTML = 'التذاكر ('+response.totaltickets+')';
			document.getElementById('totalOpened').innerText = response.totalOpened;
			document.getElementById('supportReply').innerText = response.supportReply;
			document.getElementById('totalCustomer').innerText = response.totalCustomer;
			document.getElementById('totalClosed').innerText = response.totalClosed;
		}
	});
}

	</script>
	
<?php

include 'inc/footer.php';

?>
</body>
</html>
