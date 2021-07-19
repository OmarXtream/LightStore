<?php
ob_start();
$pageName="orders";
require_once("inc/header.php");

if(rankPermission($_SESSION['staffId:light'],3,true)){
	exit(header('Location: ./home'));
}
 ?>
            <main id="main-container">
				<div class="content">

    <div class="row">
                <div class="col-md-6">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">إضافة منتج خاص</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-basket"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form  method="post" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-primary floating">
                                    <input type="text" class="form-control" id="pr_name" name="pr_name">
                                    <label for="material-color-primary2">إسم المنتج</label>
                                </div>
                             <div class="form-text text-muted text-right"> اسم المنتج الناجح دائما يساعد على التصريف الاكبر وتحقيق الاهداف المطلوبة</div>
                                </div>

                            </div>

                                <div class="form-material floating">
                                    <textarea class="form-control" id="pr_des" name="pr_des" rows="3"></textarea>
                                    <label for="material-textarea-small2">وصف مختصر للمنتج</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material form-material-sm floating">
                                    <input type="number" class="form-control form-control-sm" id="pr_price" name="pr_price">
                                    <label for="material-input-size-sm2"> السعر</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="form-material floating">
                                    <select class="form-control" id="pr_type" name="pr_type">
                                        <option></option>
                                        <option value="1"> سيارات </option>
										<option value="2"> سكربتات</option>
                                        <option value="3"> مهمات </option>
										<option value="4"> أخرى </option>
                                    </select>
                                    <label for="material-select2">إختر قسم المنتج</label>

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9">
<center>
    <button type="submit" onClick="_prlunch()" class="btn btn-alt-primary"> إطلاق المنتج <i class="si si-rocket"></i> </button>
    

</center>

</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        
        
        
        
        <div class="col-md-6">
					            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">تعديل منتج خاص</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-wrench"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form  method="post" onsubmit="return false;">
  <div class="form-group">
    <label for="exampleFormControlSelect1">إختر المنتج</label>
<form id="membersform" method="post" onsubmit="return false;" autocomplete="off">
<select class="form-control" name="pr_edit" onchange="showProduct(this.value)" data-placeholder="إختر المنتج" ><option value="">إسم المنتج </option>

<?php

$db = $database->openConnection();

    $response = $db->prepare('SELECT id,pr_name
            FROM Sproducts
           ');
$response->execute();
$pr = $response->fetchAll();
$response->CloseCursor();

if($response->rowCount() > 0 )
{
foreach($pr as $product){
$pr_id = (int)$product['id'];
$pr_name = htmlspecialchars($product['pr_name']);

echo '<option value="'.$pr_id.'">'.$pr_name.'</option>';

}
}

?>

   </select>
   
   
   <br>
   <div id="txtHint">

                        </div>

</form>
</div>
                    </form>
                </div>
            </div>

					
					</div>
					        <div class="col-md-11">

					<center>
<div class="block text-right">
<div class="block-header block-header-default">
<h3 class="block-title">سجل الطلبات  &mdash; الخاصة</h3></div>

<div class="block-content block-content-full">
<table class="table table-bordered table-striped table-vcenter js-dataTable-simple text-center" data-order='[[ 1, "DESC" ]]'>
<thead>
<tr>
<th class="">رقم الطلب</th>
<th class="d-none d-sm-table-cell">الشخص</th>
<th class="d-none d-sm-table-cell">قسم الطلب</th>

<th class="d-none d-sm-table-cell" > إسم المنتج</th>
<th class="d-none d-sm-table-cell">الحقوق</th>
<th class="d-none d-sm-table-cell" >تفاصيل</th>
<th class="d-none d-sm-table-cell">ملاحظة</th>
</tr>
</thead>
<tbody>

								<?php


$db = $database->openConnection();

    $response = $db->prepare('SELECT id,cl_id,copyrights,more,note,pr_id,pr_name,pr_type
            FROM orders
           ');
    $response->execute();
    $member = $response->fetchAll();
    $response->CloseCursor();
if($response->rowCount() > 0 )
{
foreach($member as $members){
$cl_name=current($conn->query("SELECT username FROM Customers WHERE id={$members["cl_id"]}")->fetch());

$id = (int)$members['id'];
$name = htmlspecialchars($members['pr_name']);
$c = htmlspecialchars($members['copyrights']);
$m = htmlspecialchars($members['more']);
$n = htmlspecialchars($members['note']);
$t = htmlspecialchars($members['pr_type']);


echo'
                                      <tr>
                                        <td>'.$id.'</td>
                                      <td>'.$cl_name.'</td>
										<td>'.Ptype($t).'</td>
                                      <td>'.$name.'</td>
										<td>'.$c.'</td>
     									<td>'.$m.'</td>
                                      	<td>'.$n.'</td>
                                      	



									</tr>
									';
}
}

?>

</tbody>
</table>
</div>
</div>
</div>

</center>

</div>


        <div class="col-md-11">
            <center>
					            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">تسليم الطلبات الخاصه</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-tag"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form  method="post" onsubmit="return false;">
  <div class="form-group">
<form  method="post" onsubmit="return false;" autocomplete="off">
<select class="form-control" name="order" id="order" data-placeholder="الطلب" ><option value=""> Product Name - Client - Order id</option>
<?php

$db = $database->openConnection();

    $response = $db->prepare('SELECT id,pr_name,cl_id
            FROM orders
           ');
$response->execute();
$pr = $response->fetchAll();
$response->CloseCursor();

if($response->rowCount() > 0 )
{
foreach($pr as $product){
 $cl_name=current($conn->query("SELECT username FROM Customers WHERE id={$product["cl_id"]}")->fetch());

$pr_id = (int)$product['id'];
$pr_name = htmlspecialchars($product['pr_name']);

echo '<option value="'.$pr_id.'">'.$pr_name.' - '.$cl_name.'  - #'.$pr_id.'</option>';

}
}

?>

   </select>
   

   <br>

</form>
</div>

  <div class="form-group">
    <label for="exampleFormControlSelect1">إختر المنتج</label>
<form id="membersform" method="post" onsubmit="return false;" autocomplete="off">
<select class="form-control" name="Thepr" id="Thepr"  data-placeholder="إختر المنتج" ><option value="">إسم المنتج </option>

                                        <?php
                             
                                          $dir = "../Lstorage/";

 $files = glob("$dir*.{rar,tgz,zip}", GLOB_BRACE);
   foreach($files as $file){
	   echo" <option value='".basename($file)."'>".basename($file)."</option>";
   }


   ?>

   </select>
   <br>
<button type="submit" onClick="_doneorder()" class="btn btn-alt-success"> تسليم الطلب <i class="si si-cup"></i> </button><br>

</form>
</div>




                    </form>
                </div>
            </div>

					</center>
					</div>





</div>
</main>
	
	<script>
		function _doneorder(){
		    
		
				var o=document.getElementById("order").value;
				var p=document.getElementById("Thepr").value;
			
				 if(o == null || o == "" || p == "" || p == null){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("doneorder.php", "order="+o+"&pr="+p)
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
							document.getElementById("order").value = '';
							document.getElementById("Thepr").value = '';

						}
					});
				}
		}


	</script>


<script>
function showProduct(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajax/Sshowpr.php?q=" + str + "&token=" + document.getElementsByName('token')[0].getAttribute('content'),true);
        xmlhttp.send();
    }
}
</script>
	<script>
		function _predit(id){
		    
		
				var pname=document.getElementById("p_name").value;
				var pdis=document.getElementById("p_des").value;
				var pprice=document.getElementById("p_price").value;
				var ptype=document.getElementById("p_type").value;


				 if(pname == null || pname == "" || pdis == "" || pdis == null || pprice == "" || pprice == null || typeof ptype == 'undefined'){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("Seditproduct.php", "id="+id+"&name="+pname+"&dis="+pdis+"&price="+pprice+"&type="+ptype)
					.then(function(response){
						swal({
							title: response.t, 
							text: response.m,
							type: response.tp,
							showConfirmButton: response.b,
							confirmButtonText: 'موافق'
						});
						if(response.tp == 'error'){
						            document.getElementById("txtHint").innerHTML = "";

						}else if(response.tp == 'success'){
        document.getElementById("txtHint").innerHTML = "";
						}
					});
				}
		}


	</script>
    	<script>
		
		function _prdel(id) {
			if(typeof id !== 'undefined') {
				sendData("Seditproduct", "pid="+id,'GET')
					.then(function(response){
						swal({
							title: response.t, 
							text: response.m,
							type: response.tp,
							showConfirmButton: response.b,
							confirmButtonText: 'موافق'
						});
					 if(response.tp == 'success'){
        document.getElementById("txtHint").innerHTML = "";
						}
					});
			}
		}


	</script>
		<script>
		function _prlunch(){
		    
		
				var name=document.getElementById("pr_name").value;
				var dis=document.getElementById("pr_des").value;
				var price=document.getElementById("pr_price").value;
				var type=document.getElementById("pr_type").value;

				 if(name == null || name == "" || dis == "" || dis == null || price == "" || price == null || typeof type == 'undefined' ){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("Sproduct.php", "name="+name+"&dis="+dis+"&price="+price+"&type="+type)
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
							document.getElementById("pr_name").value = '';
							document.getElementById("pr_des").value = '';
							document.getElementById("pr_price").value = '';
							document.getElementById("pr_type").value = '';

						}
					});
				}
		}


	</script>

	
	
	

<?php require_once("inc/footer.php"); ?>