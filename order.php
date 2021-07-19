<?php

include 'inc/phphead.php';

		if(!isset($_GET['p']) OR empty($_GET['p'])  OR !ctype_digit($_GET['p']) OR strlen($_GET['p']) > 32 ){
echo'<meta http-equiv="refresh" content="0; url=./index.php" />';
die;
}
    


include 'inc/head.php';
?>
	<div class="registration">
		<div class="container">
		                <div class="block">

<center>
                <?php
  			$check=$conn->prepare('SELECT id,pr_name,pr_type,pr_des,pr_price FROM Sproducts WHERE id=:idd');
			$check->bindParam(":idd", $_GET['p'], PDO::PARAM_INT);
			$check->execute();
			

if($check->rowCount() > 0){

$product =$check->fetch(PDO::FETCH_ASSOC);
$pr_id = (int)$product['id'];
$pr_name = htmlspecialchars($product['pr_name']);
$price = (int)$product['pr_price'];

?>
    
                <div class="block-header block-header-default">
                    <h3 class="block-title">تفاصيل الطلب</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-menu"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form  method="post" enctype="multipart/form-data" onsubmit="return false;">
                                <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary floating">
                                    <input type="text" class="form-control" id="name" name="name">
                                    <label for="material-color-primary2"> الإسم</label>
                                </div>
<div class="form-text text-muted text-right"> إسم السيرفر او حقوق شخص</div>                                </div>

                            </div>


                                <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary floating">
                                    <input type="text" class="form-control" id="more" name="more">
                                    <label for="material-color-primary2"> إضافة</label>
                                </div>
                                
<div class="form-text text-muted text-right">  عبارات إضافيه او حقوق صوره </div>                                </div>

                            </div>


                                <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary floating">
                                    <input type="text" class="form-control" id="notes" name="notes">
                                    <label for="material-color-primary2"> ملاحظات</label>
                                </div>

</div>

                            </div>
                            
                                                                                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary ">
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $pr_name; ?>" disabled>
                                    <input type="hidden" value="<?php echo $pr_id; ?>" name="pr_id" id="pr_id">
                                </div>
                                
<div class="form-text text-muted text-right"> إسم المنتج المراد طلبه </div>                                </div>

                            </div>


                       
                       
                                          
                        <div class="form-group row">
                            <div class="col-md-9">
<center>
  <?php  if($clientbalance >= $price){ echo'
  <button type="submit" onClick="_order()" class="btn btn-alt-primary"> <i class="si si-fire"></i>  إرسال الطلب</button>

  '; 
      
  }else{
									    echo'<button type="submit" onClick="nomoney()" class="btn btn-alt-primary"> <i class="si si-fire"></i>  إرسال الطلب</button>
';
  }
									    ?>
									
</center>

</div>
                        </div>
                    </form>
                </div>

            
<?php 
    
}else{
    
echo' <strong>لم يتم العثور على المنتج المطلوب';    
    echo'<meta http-equiv="refresh" content="1; url=./products" />';

}        
?>


</center>
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
</script>
		<script>
		function _order(){
		    
				var id=document.getElementById("pr_id").value;
				var name=document.getElementById("name").value;
				var more=document.getElementById("more").value;
				var note=document.getElementById("notes").value;

				 if(name == null || name == "" || more == "" || more == null || note == "" || note == null || typeof id == 'undefined' ){
					swal({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("makeorder.php", "name="+name+"&more="+more+"&note="+note+"&id="+id)
					.then(function(response){
						swal({
							title: response.t, 
							text: response.m,
							type: response.tp,
							showConfirmButton: response.b,
							confirmButtonText: 'موافق'
						});
						if(response.tp == 'error'){
						    
						}else if(response.tp == 'success'){
				swal({
					title: 'تنبيه', 
					text: 'قم بمراجعة قائمة مشترياتك بشكل دوري لطلبك',
					type: 'info',
					showConfirmButton: true,
					confirmButtonText: 'موافق'
				});

						}
					});
				}
		}


	</script>

<?php include 'inc/footer.php'; ?>

</body>
</html>
