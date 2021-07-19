<?php

include 'inc/phphead.php';
$nostyle = true;
$pageName = 'own';
include 'inc/head.php';
?>
<!-- about-heading -->
	<div class="about-heading">
<h2>منطقة <span>المشتريات</span></h2>
</div>
	<!-- //about-heading -->
	<div class="clients">
		<div class="container">
			<div role="tabpanel" data-example-id="togglable-tabs">

				<ul id="myTab" class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">الطلبات المنفذه</a></li>
					<li role="presentation"><a href="#files" role="tab" id="files-tab" data-toggle="tab" aria-controls="files">طلبات قيد التنفيذ</a></li>


				</ul>

				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
						<br>

						<div class="col-md-12 contact-form-right">
							<br>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>تحميل</th>
										<th> إسم المنتج</th>
										<th>#</th>
									</tr>
								</thead>
								<tbody>
									
								<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,pr_name,id_pr,code
            FROM owners
            WHERE id_cl = :cl
           ');
    $response->bindValue(':cl',(int)$_SESSION['memberId:light'],PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){

$id = (int)$members['id'];
$name = htmlspecialchars($members['pr_name']);
$code = htmlspecialchars($members['code']);
echo'
                                      <tr>
										
										<td><a target="_blank" href="download?code='.$code.'&token='.$_SESSION['_token'].'">إنقر هنا</a></td>
										<td>'.$name.'</td>						<td>'.$id.'</td>

									</tr>
									';
}
}else{
    

    echo'<center><strong><h3> لا تملك اي منتج حاليا </h3> </strong></center>';
}
?>
								</tbody>
							</table>
						</div>

						<br>
						<hr>

					</div>



					<div role="tabpanel" class="tab-pane fade" id="files" aria-labelledby="files-tab">
						<br>
						
						


							<table class="table table-striped">
								<thead>
									<tr>
									   
										<th>ملاحظات</th>
											 <th>تفاصيل</th>
											 <th>الحقوق</th>

										<th>النوع</th>
										<th>إسم  المنتج</th>
                                        <th>#</th>
									</tr>
								</thead>
								<tbody>
									
								<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,copyrights,more,note,pr_id,pr_name,pr_type
            FROM orders
            WHERE cl_id = :cl
           ');
    $response->bindValue(':cl',(int)$_SESSION['memberId:light'],PDO::PARAM_INT);
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){
$id = (int)$members['id'];
$name = htmlspecialchars($members['pr_name']);
$c = htmlspecialchars($members['copyrights']);
$m = htmlspecialchars($members['more']);
$n = htmlspecialchars($members['note']);
$t = htmlspecialchars($members['pr_type']);


echo'
                                      <tr>
                                      	<td>'.$n.'</td>
     									<td>'.$m.'</td>
										<td>'.$c.'</td>
										<td>'.Ptype($t).'</td>
                                      <td>'.$name.'</td>
                                      <td>'.$id.'</td>



									</tr>
									';
}
}else{
    

    echo'<center><strong><h3> ليس لديك اي طلبات خاصه حاليا </h3> </strong></center>';
}

?>
								</tbody>
							</table>






						
						
										</div>

						</div>

				
				
			</div>
		</div>
	</div>
	
<?php 
$database->closeConnection();

include 'inc/footer.php'; 
?>

</body>
</html>
