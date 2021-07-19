<?php
ini_set('session.cookie_httponly', true);
session_start();
require_once("inc/db.php");


if(isset($_SESSION['memberId:light'])){
	exit(header('Location: index'));
} else if(isset($_GET['status'])) {
	if($_GET['status'] == 1){
		$what = 1;
	} else if($_GET['status'] == 2){
		$what = 2;
	} else if($_GET['status'] == 3){
		$what = 3;
	}
}


$_SESSION['_token']=bin2hex(openssl_random_pseudo_bytes(16));

require_once("inc/head.php");

?>




<!-- about-heading -->
	<div class="about-heading">
		<h2>تسجيل <span>دخول</span></h2>
	</div>
	<!-- //about-heading -->
	<div class="registration">
		<div class="container">
			<div class="signin-form profile">
				<h3>تسجيل الدخول</h3>
				<div class="login-form">
					<form class="js-validation-signin text-right" autocomplete="off" onSubmit="return false;">
						<input type="text"  id="login-username" name="login-username" placeholder="البريد" required="">
						<input type="password"  id="login-password" name="login-password" placeholder="كلمة السر" required="">
<br>
					<center><div class="g-recaptcha" data-sitekey="<?=$Config["reCAPTCHA"];?>"></div></center>

						<div class="tp">
							<input type="submit" onClick="login()" value="تسجيل دخول  ">

						</div>
					</form>
				</div>
                     <center>
                            <div class="col-md-3">
				<p><a href="terms"  data-target="#modal-terms"> <i class="fa fa-book text-muted mr-5"></i> قراءة القوانين</a></p>
				</div>
                            <div class="col-md-3">

				<p><a href="registration"><i class="fa fa-user text-muted mr-5"></i> إنشاء حساب</a></p>
				</div>
                            <div class="col-md-3">

				<p><a href="forgetpass"><i class="fa fa-key text-muted mr-5"></i> نسيت كلمة المرور</a></p>
                               </div>
                  </center>


			</div>
		</div>
	</div>

<?php

include 'inc/footer.php';

?>



<script>
		<?php if(isset($_GET['timeout']) && $_GET['timeout'] == 1){ ?>	
		alertify.logPosition("top right");
		alertify.error("تم تسجيل خروجك بنجاح، نظرًأ لعدم تفاعلك سجل مجددا!");
	 <?php } ?>
		<?php if(isset($what) && $what == 1) { ?>
			toast({
				type: 'success',
				title: 'تم إرسال كلمة مرور جديدة إلى البريد الإلكتروني'
			});	
		<?php } else if(isset($what) && $what == 2){ ?>
			toast({
				type: 'error',
				title: 'حدث خطأ إثناء ارسال البريد الإلكتروني، اعد المحاولة.'
			});			
		<?php } else if(isset($what) && $what == 3){?>
			toast({
				type: 'error',
				title: 'انتهت صلاحية رابط التحقق الخاصة بالبريد الإلكتروني'
			});		
		<?php } ?>
	function login(){
		var useroremail=document.getElementById("login-username").value;
		var password=document.getElementById("login-password").value;
		if(useroremail == null || useroremail == "" || password == "" || password == null){
			swal({
				title: "خطأ",
				text: "عذراً ولكن البيانات غير مكتمله",
				type: "error"
			});
		}else if (grecaptcha === undefined) {
			swal({
			  title: "خطأ",
			  text: "عذراً ولكن التحقق غير موجود",
			  type: "error"
			});
			setTimeout(function () { location.href = "login";}, 3000);
			throw new Error("Empty RECAPTCHA");
		}
		var response = grecaptcha.getResponse();
		if (!response) {
			swal({
				title: "خطأ",
				text: "من فضلك تحقق من أنك لست روبوت",
				type: "error"
			});
			throw new Error("Robot Check");
		}
		sendData("login.php", "useroremail="+useroremail+"&password="+password+"&reCAPTCHA="+grecaptcha.getResponse())
		.then(function(response){
console.log(response) 
			swal({
				title: response.t, 
				text: response.m,
				type: response.tp,
				showConfirmButton: response.b,
				confirmButtonText: 'موافق'
			});
			if(response.tp == 'error'){
				grecaptcha.reset();
			}else if(response.tp == 'success'){
				setTimeout(function () { location.href = "./";}, 3000);
			}
		});
	}
		</script>
		<script src="https://unpkg.com/sweetalert2@7.21.1/dist/sweetalert2.all.js"></script>
                <script src="js/jquery.validate.min.js"></script>
		<script src="js/op_auth_signin.js"></script>


</body>
</html>
