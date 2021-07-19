<?php
$pageName = "addproduct";
ob_start();
require_once("inc/header.php");
if(!rankPermission($_SESSION['staffId:light'],5,true) && !rankPermission($_SESSION['staffId:light'],4,true)){
	exit(header('Location: ./home'));
}

?>

    <main id="main-container">
<div class="content">
    <center><h2 class="content-heading">المنتجات</h2></center>
    <div class="row">
        <div class="col-md-6">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">إضافة منتج</h3>
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
                            <div class="col-8">
                                 <div class="form-material floating">
                                    <select class="form-control" id="pr_file" name="pr_file">
                                        <option></option>
                                        <?php
                             
                                          $dir = "../Lstorage/";

 $files = glob("$dir*.{rar,tgz,zip}", GLOB_BRACE);
   foreach($files as $file){
	   echo" <option value='".basename($file)."'>".basename($file)."</option>";
   }


   ?>
                                       </select> 
                                    <label for="material-select2">إختر المنتج</label>
                                </div>

                            </div>
                        </div>
                                                <div class="col-6">
                            <label class="css-control css-control-sm css-control-secondary css-switch">
                                <input id='mh' name='mh' type="checkbox" class="css-control-input" >
                                <span class="css-control-indicator"></span> مشفر
                            </label>
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
                    <h3 class="block-title">تعديل منتج</h3>
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

    $response = $db->prepare('SELECT id,name
            FROM products
           ');
$response->execute();
$pr = $response->fetchAll();
$response->CloseCursor();

if($response->rowCount() > 0 )
{
foreach($pr as $product){
$pr_id = (int)$product['id'];
$pr_name = htmlspecialchars($product['name']);

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
    </div>
</div>
    </main>
	
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
        xmlhttp.open("GET","ajax/showpr.php?q=" + str + "&token=" + document.getElementsByName('token')[0].getAttribute('content'),true);
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
				var pfile=document.getElementById("p_file").value;
				var pmh=document.getElementById("pmh").checked;
				
if(pmh){
var pm = 2;
}else{
    var pm=1;
}

				 if(pname == null || pname == "" || pdis == "" || pdis == null || pprice == "" || pprice == null || typeof ptype == 'undefined' || typeof pfile == 'undefined'){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("editproduct.php", "id="+id+"&name="+pname+"&dis="+pdis+"&price="+pprice+"&m="+pm+"&type="+ptype+"&file="+pfile)
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
				sendData("editproduct", "pid="+id,'GET')
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
	/*
				function hasExtension(inputID, exts) {
    var fileName = document.getElementById(inputID).value;
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
}

				 if (!hasExtension('pr_file', ['.rar', '.tgz', '.zip'])) {
					toast({
					  type: 'error',
					  title: 'لا يسمح ب ل هذا النوع من الملفات'
					});
            */
		function _prlunch(){
		    
		
				var name=document.getElementById("pr_name").value;
				var dis=document.getElementById("pr_des").value;
				var price=document.getElementById("pr_price").value;
				var type=document.getElementById("pr_type").value;
				var file=document.getElementById("pr_file").value;
				var mh=document.getElementById("mh").checked;
				
if(mh){
var m = 2;
}else{
    var m=1;
}
				 if(name == null || name == "" || dis == "" || dis == null || price == "" || price == null || typeof type == 'undefined' || typeof file == 'undefined'){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});

				


				}else{
					sendData("product.php", "name="+name+"&dis="+dis+"&price="+price+"&m="+m+"&type="+type+"&file="+file)
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
							document.getElementById("pr_file").value = '';
							document.getElementById("mh").checked = false;

						}
					});
				}
		}


	</script>

<?php require_once("inc/footer.php"); ?>