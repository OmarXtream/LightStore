<?php 
require_once('inc/req.php');
$req = true;
require_once('inc/class/paypal.php');
require_once('inc/class/httprequest.php');
if(isset($_POST['checkout'],$_POST['price'])){
		$prices = explode(',',$Config['prices']);
	if(in_array($_POST['price'],$prices)){
		$price = $_POST['price'];
		$r = new PayPal(true);
		$ret = ($r->doExpressCheckout($price, ' لقد قمت بشراء '.$price.' نقطة '));
	}else{
		$error[3] == true;
	}
}
if(isset($_GET['token'],$_GET['PayerID'])){
$r = new PayPal(true);


$final = $r->doPayment();

if ($final['ACK'] == 'Success') {
	$response = $r->getCheckoutDetails($final['TOKEN']);
$db = $database->openConnection();
$price = str_replace("|USD|", "", $response['CUSTOM']);
$money = taxCalc($price,0.15,2);
$time = time();
print_r($final);
print_r($response);
$db->query("INSERT INTO payments (id,cid,price,method,email,status,token,payerid,country,time) VALUES ('',{$_SESSION['memberId:light']},'{$price}',1,'{$response['EMAIL']}',1,'{$response['TOKEN']}','{$response['PAYERID']}','{$response['COUNTRYCODE']}',{$time})");
$db->query("UPDATE Customers SET Credits=Credits + {$money} WHERE id={$_SESSION['memberId:light']}");
$_SESSION['buystatus'] = 'success';
$database->closeConnection();
} else {
$_SESSION['buystatus'] = 'error';
}
}elseif(isset($_GET['token'])){
}
require_once("inc/phphead.php");
$pageName = 'payment';
require_once("inc/head.php");

?>
<main id="main-container" style="background-color:#f0f2f5;">
      <div class="bg-info">
         <div class="bg-pattern bg-black-op-25" style="background-image: url('assets/media/various/bg-pattern.png');">
            <div class="content text-center">
               <div class="pt-50 pb-20">
                  <h1 class="font-w700 text-white mb-10">Light &mdash; شحن رصيد</h1>
<h2 class="h4 font-w400 text-white-op">$<?php echo $clientbalance; ?> :الرصيد الحالي</h2>               </div>
            </div>
         </div>
      </div>
      <div class="content ">
         <div class="row">

			<div class="col-lg-4">
				<div class="block" dir="ltr">
					<ul class="nav nav-tabs nav-tabs-block nav-fill" data-toggle="tabs" role="tablist">
					<?php
					$paidWay = 0;
					if(!$closepaypal){
					?>
						<li class="nav-item">
							<a class="nav-link <?= $paidWay == 0 ? 'active' : '';?>"><img src="images/paypal.png"style="height:32px;width:32px:"/></a>
						</li>
						<?php
					}else{
						$paidWay++;
					}
					?>
					</ul>
					<div class="block-content tab-content" dir="rtl" align="center">
					<?php
					$paidWayBox = 0;
					if(!$closepaypal){
					?>
						<div class="tab-pane <?= $paidWayBox == 0 ? 'active' : '';?>" id="btabs-static-home" role="tabpanel">
							<h4 class="font-w400">الدفع الإلكتروني عن طريق البايبال</h4>
							<small style="color:#D3D3D3">يمكنك الدفع الإلكتروني من الحصول على الرصيد مباشره بعد الدفع</small>
							<form method="post">
								<div class="col-lg-12">
									<input type="text" class="js-range-slider" name="price" data-grid="true" data-from="2" data-values="<?=$Config['prices']?>" data-prefix="$">
									
									
								</div>
								<br>
								<center>
									<button  name="checkout" type="submit" onclick="wait()" class="btn btn-warning waves-effect waves-light">
										<img src="images/paypal-btn.png" width="20px"></img>
										 الدفع الآن
									</button>
								</center>
								<br>
							</form>
						</div>
						<?php
					}else{
						$paidWayBox++;
					}

						?>
					</div>
				</div>
			</div>
			<div class="col-lg-8"> 
			<div class="block text-right">
				<div class="block-header block-header-default">
					<h3 style="color:#D3D3D3" class="block-title">سجل المدفوعات</h3>
				</div>
				<div class="block-content">
					<div class="table-responsive">
					<table class="table table-bordered table-striped table-vcenter js-dataTable-simple text-center" id="tablePayments">
						<thead>
							<tr>
								<th class="" aria-sort="descending" aria-controls="tablePayments">الفاتورة</th>
								<th>الرصيد</th>
								<th>الشركة</th>
								<th>الحاله</th>
								<th class="">التاريخ</th>
								<th class="">الإعدادات</th>
							</tr>
						</thead>
						<tbody>
<?php
$db = $database->openConnection();
$sql = $db->query("SELECT * FROM payments WHERE cid={$_SESSION['memberId:light']} ORDER BY id DESC");
foreach($sql as $row){
	$date = date('m/d/Y', $row['time']);
	$disabled = '';
	$href = '?pid='.$row['id'];
	switch($row['method']){
		case 1:
			$method = "paypal";
		break;
		case 2:
			$method = "stc";
		break;
		case 3:
			$method = "mobily";
		break;
	}
	switch($row['status']){
		case 1:
			$status = "مكتمل";
			$bdgcolor = "success";
			$disabled='disabled';
			$href='';
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
			$disabled='disabled';
		break;
	}
	echo"
                                    <tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['price']}$</td>
                                        <td>{$method}</td>
                                        <td><span class='badge badge-{$bdgcolor}'>{$status}</span></td>
										<td>{$date}</td>
										<td class='text-center'>
											<a href='{$href}' class='btn btn-sm btn-secondary {$disabled}' data-toggle='tooltip' title='' data-original-title='حالة الطلب' {$disabled}>
												<i class='fa fa-info'></i>
											</a>
										</td>
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
   </div>
   </div>
</main> 
<?php
if(isset($_GET['type'],$_SESSION['buystatus'])){
	if($_GET['type'] == 'success' and $_SESSION['buystatus'] == 'success'){
	unset($_SESSION['buystatus']);
	alertprinter(2);
	}elseif($_GET['type'] == 'error' and $_SESSION['buystatus'] == 'error'){
	unset($_SESSION['buystatus']);
	alertprinter(1);
	}
}
?>

<script>
   var cid = 0;
   var cards = [];
   var cardNumbers = [];
function wait(){
swal({
  title: 'إنتظر',
  imageUrl: 'images/paypal.png',
  imageHeight: 200,
  allowOutsideClick: false,
  allowEscapeKey: false,
  text: 'إنتظر قليلا من فضلك جاري تحويلك للموقع المطلوب',
  timer: 9000,
  confirmButtonText: null,
  onOpen: () => {
    swal.showLoading()
  }
});
}
function getUrlData(){
	var data = [];
	var url_string = window.location.href; 
	var url = new URL(url_string);
	var price = url.searchParams.get("price");
	var companytype = url.searchParams.get("type");
	data['price'] = price * 8;
	data['dollarPrice'] = price;
	data['companytype'] = companytype;
	return data;
}
 function calculate(id){
	var resualt = 0;
	price = getUrlData()['price'];
	if(document.getElementById('cardtype'+id).value == 'none'){
		cards.splice(id, 1)
		console.log(cards[id]);
	}else{
		cards[id] = document.getElementById('cardtype'+id).value;
	}
	cards.forEach(function(crd){
		console.log(parseInt(crd));
		resualt += parseInt(crd);
	});
	document.getElementById('resualt').innerHTML = resualt+' / '+price;
	if(resualt >= price){
		document.getElementById('sendorder').disabled = false;
		document.getElementById('addCardButton').disabled = false;
	}
 }
 function insertCardNumber(e){
	 var numberInArray = e.getAttribute("number");
	 cardNumbers[numberInArray] = e.value;
 }
 


</script>
<?php require_once("inc/footer.php");?>
<body>
</body>