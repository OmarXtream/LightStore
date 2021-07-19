<?php
$pageName="up";
ob_start();
require_once("inc/header.php");
if(!rankPermission($_SESSION['staffId:light'],5,true) OR rankPermission($_SESSION['staffId:light'],1,true)){
	exit(header('Location: ./home'));
}
?>
    <main id="main-container">
<div class="content"> <center><h2 class="content-heading">إدارة المنتجات</h2></center>    <div class="row">
        <div class="col-md-6">
            <div class="block">
<div class="block-header block-header-default"> 
<h3 class="block-title">رفع منتج</h3>
<div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-cloud-upload"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    
                    
                    
                                         <form class="dropzone" id="myDrop" >
                                        <div class="fallback">
                                            <input name="file" type="file" accept=".rar,.zip,.tgz"/>
                                        </div>
                                    </form>

                    
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">حذف منتج</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    
                                                                    <div class="form-group row">
                            <div class="col-8">
                                 <div class="form-material floating">
                                    <select onchange="delProduct(this.value)" class="form-control" id="pr_file" name="pr_file">
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

                    
                    
</div>
                    </form>
                </div>
                <div id="txtHint"></div>
            </div>
        </div>
    </div>
</div>
    </main>

<script>
	new Dropzone("form#myDrop", { 
		maxFiles: 3, 
		uploadMultiple :false,
		maxFilesize: 10,
		url: "ajax/upload.php",
        acceptedFiles: ".zip,.tgz,.rar",
        success: function(file, response){
			swal({
				title: response.t,
				text: response.m,
				type: response.tp,
				confirmButtonText: 'موافق'
			});
		}
	});
</script>
<script>
function delProduct(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
Swal.fire({
  title: 'هل أنت متاكد؟',
  text: "الرجاء مراعاة تحملك مسؤولية حذف المنتج" +str,
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  cancelButtonText: 'تراجع',
  confirmButtonText: 'تأكيد الحذف!'
}).then((result) => {
  if (result.value) {

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
        xmlhttp.open("GET","ajax/unup.php?q=" + str + "&token=" + document.getElementsByName('token')[0].getAttribute('content'),true);
        xmlhttp.send();
        
      }
});

        
    }
}
</script>



<?php require_once("inc/footer.php"); ?>