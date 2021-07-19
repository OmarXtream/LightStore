<?php
$pageName = "payments";
ob_start();
require_once("inc/header.php"); 
if(!rankPermission($_SESSION['staffId:light'],5,true)){
	exit(header('Location: ./home'));
}
?>
<main id="main-container">
	<div class="content">
	<?php
	if(isset($_GET['pid']) and ctype_digit($_GET['pid']) and strlen($_GET['pid']) < 30 and !empty($_GET['pid'])){
		$pid = $_GET['pid'];
		$conn = $database->openConnection();
		$sql = $conn->prepare('SELECT b.id,b.cid,b.price,b.method,b.status,b.time,sa.username FROM payments b INNER JOIN Customers sa on sa.id = b.cid AND b.id = :pid AND b.method <> 1 AND b.status <> 1 AND b.status <> 5');
		$sql->bindValue(':pid',$pid);
		$sql->execute();
		if($sql->rowCount() != 1){
			exit(header('Location: ./home'));
		}else{
			$row = $sql->fetch();
			$statement = $conn->query('SELECT card,type,status,id FROM cardsPayments WHERE pid='.$row['id']);
			if($statement->rowCount() == 0){
				exit(header('Location: ./home'));
			}else{
				$method = $row['method'] == 2 ? 'stc' : 'mobily';

	switch($row['status']){
		case 1:
			$status = "مكتمل";
		break;
		case 2:
			$status = "بطاقة خاطئة";
		break;
		case 3:
			$status = "جاري التنفيذ";
		break;
		case 4:
			$status = "في الإنتظار";
		break;
		case 5:
			$status = "ملغي‬‎";
		break;
	}
	?>
		<div class="row text-center">
				<br><br>
			<div class="col-md-12 pt-5"><center><h4 class="text-muted">العضو : <?=htmlspecialchars($row['username'])?></h4></center></div>
			<div class="col-md-6"><center><br>
				<div class="col-md-6">الشركة : <?=$method?></div><br>
				<div class="col-md-6">حالة الطلب : <span id="orderStatus"><?=$status?></span></div><br>
				<div class="col-md-6">المال المطلوب : <?=$row['price']?>$</div></center><br><br>
			</div>
			<div class="col-md-6"><center><br>
				<div class="col-md-6">المدفوع : <?=$row['price']?></div><br>
				<div class="col-md-6">عدد البطائق : <?=$statement->rowCount();?></div><br>
				<div class="col-md-6">منذ : <?=ago($row['time']);?></div></center><br><br>
			</div>
			
			<div class="col-md-12">
				<div class="block">
					<div class="block-header block-header-default">
						<h3 class="block-title">معلومات البطائق</h3>
					</div>
					<div class="block-content">
						<div class="table-responsive">
							<table class="table table-striped table-vcenter">
								<thead>
									<tr>
										<th>#</th>
										<th style="width: 30%;">النوع</th>
										<th style="width: 15%;">الرقم</th>
										<th class="text-center">الحالة</th>
										<th class="text-center">الإجراء</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$i = 1;
								foreach($statement as $stmt){
									switch($stmt['status']){
										case 1:
											$status = "بطاقة صحيحة";
											$bdgcolor = "success";
										break;
										case 2:
											$status = "بطاقة خاطئة";
											$bdgcolor = "warning";
										break;
										case 3:
											$status = "جاري التنفيذ";
											$bdgcolor = "primary";
										break;
										case 4:
											$status = "في الإنتظار";
											$bdgcolor = "info";
										break;
										case 5:
											$status = "ملغي‬‎";
											$bdgcolor = "danger";
										break;
									}
									echo'
									<tr>
										<td class="font-w600">'.$i++.'</td>
										<td class="font-w600">'.$stmt['type'].'</td>
										<td>'.$stmt['card'].'</td>
										<td id="card'.$stmt['id'].'status">
											<span class="badge bg-'.$bdgcolor.' text-white">'.$status.'</span>
										</td>
										<td class="text-center" dir="ltr">
											<div class="btn-group">
												<button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="" data-original-title="البطاقة صحيحه" onclick="changeCardStatus(1,'.$stmt['id'].')">
													<i class="fa fa-check"></i>
												</button>
												<button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="" data-original-title="البطاقة خاطئة" onclick="changeCardStatus(2,'.$stmt['id'].')">
													<i class="fa fa-times"></i>
												</button>
											</div>
										</td>
									</tr> 
									';
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="block">
					<div class="block-header block-header-default">
						<h3 class="block-title">إجراءات الطلب</h3>
					</div>
					<div class="block-content">
						<div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-material">
                                    <select class="form-control" id="newOrderStatus" name="material-select">
                                        <option></option>
                                        <option value="1">مكتمل</option>
                                        <option value="2">بطاقة خاطئة</option>
                                        <option value="3">جاري التنفيذ</option>
                                        <option value="4">في الإنتظار</option>
                                        <option value="5">ملغي‬‎</option>
                                    </select>
                                    <label for="material-select" style="right: 0;">حالة الطلب</label>
                                </div><br>
								<button type="button" class="btn btn-alt-primary" onclick="changeOrderStatus(<?=$_GET['pid']?>)">تغيير الحالة</button>
                            </div>
                            <div class="col-md-6">
                                <div class="form-material">
									<button type="button" class="btn btn-alt-success" onclick="checkout(<?=$_GET['pid']?>)">تسديد المبلغ</button>
								</div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	<?php 
			}			
		}
	}else{
	?>
		
		<div class="row text-center">
			<div class="col-lg-12"> 
			<div class="block text-right">
				<div class="block-header block-header-default">
					<h3 class="block-title">سجل مدفوعات البطائق مسبقة الشحن</h3>
				</div>
				<div class="block-content">
					<div class="table-responsive" style="">
					<table class="table table-bordered table-striped table-vcenter js-dataTable-simple text-center" id="tablePayments">
						<thead>
							<tr>
								<th class="" aria-sort="descending" aria-controls="tablePayments">الفاتورة</th>
								<th class="" aria-sort="descending" aria-controls="tablePayments">الإسم</th>
								<th>الرصيد</th>
								<th>المدفوع</th>
								<th>الحاله</th>
								<th>التاريخ</th>
							</tr>
						</thead>
						<tbody>
<?php
$conn = $database->openConnection();
$sql = $conn->query('SELECT b.id,b.cid,b.price,b.method,b.status,b.time,sa.username FROM payments  b INNER JOIN Customers sa on sa.id = b.cid AND (b.method=2 OR b.method=3) AND b.status <> 1 AND b.status <> 5');
foreach($sql as $row){
	$id = sprintf("%06d", $row['id']);
	$date = date('m/d/Y', $row['time']);
	if($row['method'] == 1){$method = "paypal";}elseif($row['method'] == 2){$method = "stc";}elseif($row['method'] == 3){$method = "mobily";}
	if($row['status'] == 1){$status = "مكتمل";$bdgcolor = "success";}elseif($row['status'] == 2){$status = "بطاقة خاطئة";$bdgcolor = "warning";}elseif($row['status'] == 3){$status = "جاري التنفيذ";$bdgcolor = "primary";}
	elseif($row['status'] == 4){$status = "في الإنتظار";$bdgcolor = "info";}elseif($row['status'] == 5){$status = "ملغي‬‎";$bdgcolor = "danger";}
	echo"
                                    <tr>
									
                                        <td><a href='?pid={$row['id']}'>{$id}</a></td>
                                        <td><a href='?pid={$row['id']}'>".htmlspecialchars($row['username'])."</a></td>
                                        <td>{$row['price']}$</td>
                                        <td>{$method}</td>
                                        <td><span class='badge badge-{$bdgcolor}'>{$status}</span></td>
										<td>{$date}</td>
                                   </tr>
	";
}
$database->closeConnection();

?>
						</tbody>
					</table>
				</div>
				</div>
			</div>
         </div>
         </div>
		
	<?php
		}
	?>
	</div>
</main>
	

	<script>
		function changeCardStatus(status,id){
			if((status != 1 && status != 2) || id.length > 30 || id.length < 1){
				swal({
					title: 'خطأ',
					text: 'من فضلك تأكد من المدخلات',
					type: 'success',
					confirmButtonText: 'موافق'
				});
			}else{
				sendData("payments.php", "cardStatus="+status+"&cardId="+id,'GET')
				.then(function(response){
					swal({
						title: response.t,
						text: response.m,
						type: response.tp, 
						confirmButtonText: response.b
					});
					if(response.tp == 'success'){
						document.getElementById('card'+id+'status').innerHTML = status == 1 ? '<span class="badge bg-success text-white">بطاقة صحيحة</span>' : '<span class="badge bg-warning text-white">بطاقة خاطئة</span>';
					}
				});
			}
		}
		function changeOrderStatus(id){
			var status = document.getElementById('newOrderStatus').value;
			if(status < 1 || status > 5 || id.length > 30 || id.length < 1){
				swal({
					title: 'خطأ',
					text: 'من فضلك تأكد من المدخلات',
					type: 'success',
					confirmButtonText: 'موافق'
				});
			}else{
				sendData("payments.php", "orderStatus="+status+"&pid="+id,'GET')
				.then(function(response){
					swal({
						title: response.t, 
						text: response.m,
						type: response.tp,
						confirmButtonText: response.b
					});
					if(response.tp == 'success'){
						document.getElementById('orderStatus').innerText = response.newStatus;
					}
				});
			}
		}
		function checkout(id){
			if(id.length > 30 || id.length < 1){
				swal({
					title: 'خطأ',
					text: 'من فضلك تأكد من المدخلات',
					type: 'success',
					confirmButtonText: 'موافق'
				});
			}else{
				sendData("payments.php", "checkout="+id,'GET')
				.then(function(response){
					if(response.tp == 'success'){
						swal({
							title: 'تم التسديد', 
							text: 'تم تسديد المبلغ بنجاح انتظر من فضلك',
							type: 'success',
							showConfirmButton: false,
							allowEscapeKey: false,
							allowEnterKey: false
						});
						window.setTimeout(function () {
							location.href = window.location.href.split('?')[0];
						}, 3000);
					}else{
						swal({
							title: response.t, 
							text: response.m,
							type: response.tp,
							confirmButtonText: response.b
						});
					}
				});
			}
		}


	</script>

<?php require_once("inc/footer.php"); ?>