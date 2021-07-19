<?php
ini_set('session.cookie_httponly', true);
session_start();
require_once("inc/db.php");


if(isset($_SESSION['memberId:light'])){
	exit(header('Location: home'));
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
<strong><h4>يرجى كتابة البريد الإلكتروني</h4></strong><hr>
				<div class="login-form">
					<form class="js-validation-signin text-right" autocomplete="off" onSubmit="return false;">
						<input type="text"  id="email" name="email" placeholder="البريد الإلكتروني" required="">
<br>
					<center><div class="g-recaptcha" data-sitekey="<?=$Config["reCAPTCHA"];?>"></div></center>

						<div class="tp">
							<input type="submit" onClick="forgetpass()" value="إرسال رسالة تحقق  ">

						</div>
					</form>
				</div>


			</div>
		</div>
	</div>

<?php

include 'inc/footer.php';

?>


<script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>
<script>
		const toast = swal.mixin({
		  toast: true,
		  position: 'top-end',
		  showConfirmButton: false,
		  timer: 3000
		});

	function forgetpass(){
		var email=document.getElementById("email").value;
		if(email == null){
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
			setTimeout(function () { location.href = "login.php";}, 3000);
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
		sendData("forgetpass", "email="+email+"&reCAPTCHA="+grecaptcha.getResponse())
		.then(function(response){
			swal({
				title: response.t, 
				text: response.m,
				type: response.tp,
				showConfirmButton: response.b,
				confirmButtonText: 'موافق'
			});
			if(response.updateCAPTCHA){
				grecaptcha.reset();
			}
		});
	}
		</script>
		<script src="https://unpkg.com/sweetalert2@7.21.1/dist/sweetalert2.all.js"></script>
                <script src="js/jquery.validate.min.js"></script>
		<script src="js/op_auth_signin.js"></script>


</body>
</html>
