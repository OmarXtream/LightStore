<?php
include 'inc/phphead.php';
 $nostyle = true;
include 'inc/head.php';




		if(!isset($_GET['t']) OR empty($_GET['t'])  OR !ctype_digit($_GET['t']) OR strlen($_GET['t']) > 32 ){
echo'<meta http-equiv="refresh" content="0; url=./index.php" />';
die;
}else{
    

    $tpr = Ptype($_GET['t']);

    
}



?>
	<!-- about-heading -->
	<div class="about-heading">
		<h2><?php echo $tpr; ?></h2>
	</div>
	<!-- //about-heading -->

	<div class="shared-grid">
		<div class="container">
			<div role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">مشفرة</a></li>
					<li role="presentation"><a href="#files" role="tab" id="files-tab" data-toggle="tab" aria-controls="files">غير مشفره</a></li>


					<li role="presentation"><a href="#admin" role="tab" id="admin-tab" data-toggle="tab" aria-controls="admin">طلب خاص</a></li>
				</ul>


				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
						<br>
<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,name,describes,price,type
            FROM products
            WHERE type = :nom AND enc = 2
           ');
    $response->bindValue(':nom',(int)$_GET['t'],PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){

$id = (int)$members['id'];
$name = htmlspecialchars($members['name']);
$type = (int)$members['type'];
$des = htmlspecialchars($members['describes']);
$price = (int)$members['price'];


echo'
						<div class="col-md-3 price-grid">
							<div class="price-block agile">
								<div class="price-gd-top pric-clr'.$type.'">
									<h4>'.$name.'</h4> <h5>$'.$price.' </h5>
								</div>
								<div class="price-gd-bottom">
									<div class="price-list productsImg">
										<ul>
											<img src="https://3.top4top.net/p_1247y2y591.png" alt="" height="100" width="100">
											<br>
<br> <p>'.$des.'</p>
</ul>
									</div>
									<div class="price-selet pric-sclr'.$type.'">';
									if($clientbalance >= $price){ echo'<a onClick="buy('.$id.');">شراء</a>'; }else{
									    echo'<a onClick="nomoney();">شراء</a>';
									}
									echo'
									</div>
								</div>
							</div>
						</div>


';

}
}else{

    echo'<center><strong><h3> لا يوجد منتج من هذا النوع حاليا </h3> </strong></center>';
}
?>
						


					</div>
					<div role="tabpanel" class="tab-pane fade" id="files" aria-labelledby="files-tab">
						<br>
						
						
<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,name,describes,price,type
            FROM products
            WHERE type = :nom AND enc = 1
           ');
    $response->bindValue(':nom',(int)$_GET['t'],PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){

$id = (int)$members['id'];
$name = htmlspecialchars($members['name']);
$type = (int)$members['type'];
$des = htmlspecialchars($members['describes']);
$price = (int)$members['price'];


echo'
						<div class="col-md-3 price-grid">
							<div class="price-block agile">
								<div class="price-gd-top pric-clr'.$type.'">
									<h4>'.$name.'</h4> <h5>$'.$price.' </h5>
								</div>
								<div class="price-gd-bottom">
									<div class="price-list productsImg">
										<ul>
											<img src="https://3.top4top.net/p_1247y2y591.png" alt="" height="100" width="100">
											<br>
<br> <p>'.$des.'</p>
</ul>
									</div>
									<div class="price-selet pric-sclr'.$type.'">';
									if($clientbalance >= $price){ echo'<a onClick="buy('.$id.');">شراء</a>'; }else{
									    echo'<a onClick="nomoney();">شراء</a>';
									}
									echo'
									</div>
								</div>
							</div>
						</div>


';

}
}else{
    

    echo'<center><strong><h3> لا يوجد منتج من هذا النوع حاليا </h3> </strong></center>';
}
?>
				</div>		
						
						
					<div role="tabpanel" class="tab-pane fade" id="admin" aria-labelledby="admin-tab">
						<br>
						
						
<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,pr_name,pr_des,pr_price,pr_type
            FROM Sproducts
            WHERE pr_type = :nom
           ');
    $response->bindValue(':nom',(int)$_GET['t'],PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){

$id = (int)$members['id'];
$name = htmlspecialchars($members['pr_name']);
$type = (int)$members['pr_type'];
$des = htmlspecialchars($members['pr_des']);
$price = (int)$members['pr_price'];


echo'
						<div class="col-md-3 price-grid">
							<div class="price-block agile">
								<div class="price-gd-top pric-clr'.$type.'">
									<h4>'.$name.'</h4> <h5>$'.$price.' </h5>
								</div>
								<div class="price-gd-bottom">
									<div class="price-list productsImg">
										<ul>
											<img src="https://3.top4top.net/p_1247y2y591.png" alt="" height="100" width="100">
											<br>
<br> <p>'.$des.'</p>
</ul>
									</div>
									<div class="price-selet pric-sclr'.$type.'">';
									if($clientbalance >= $price){ echo'<a href="order?p='.$id.'">شراء</a>'; }else{
									    echo'<a onClick="nomoney();">شراء</a>';
									}
									echo'
									</div>
								</div>
							</div>
						</div>


';

}
}else{

    echo'<center><strong><h3> لا يوجد منتج من هذا النوع حاليا </h3> </strong></center>';
}
$database->closeConnection();

?>
						
						
						
						</div>

						
						
						
						
						
						
						
						
					</div>
				</div>
			</div>
		</div>





<script>
	function nomoney() {

				swal({
					title: 'للاسف', 
					text: 'لا يوجد لديك رصيد كافي',
					type: 'info',
					showConfirmButton: true,
					confirmButtonText: 'موافق'
				});




}
	function buy(id) {
		if(typeof id != 'undefined'){
		    Swal.fire({
  title: 'هل أنت متاكد؟',
text: "سوف يتم خصم سعر المنتج من رصيدك وتسليمك المنتج مباشره" ,
type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  cancelButtonText: 'تراجع',
  confirmButtonText: 'تأكيد الشراء'
}).then((result) => {
  if (result.value) {

			if(id == null || id == ""){
				alertify.logPosition("bottom right");
				alertify.error("تأكد من المدخلات..");
				throw new Error('حدث خطأ!')
			} 
			sendData("buy.php", "id="+id)
			.then(function(response){
				swal({
					title: response.t, 
					text: response.m,
					type: response.tp,
					showConfirmButton: response.b,
					confirmButtonText: 'موافق'
				});
			});
  }
});
		} else { console.log('heehe'); }
	}
		
	
</script>




<?php

include 'inc/footer.php';

?>
</body>
</html>
