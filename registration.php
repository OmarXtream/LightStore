<?php
ini_set('session.cookie_httponly', true);
session_start();
require_once("inc/db.php");
require_once("inc/config.php");


if(isset($_SESSION['memberId:light'])){
	exit(header('Location: index'));
}else if(isset($_SESSION['account'])){
	exit(header('Location: login'));
}else{}

if(!isset($_SESSION['_token'])){
	$_SESSION['_token']=bin2hex(openssl_random_pseudo_bytes(16));
}
if(isset($_GET['verify'])){
	
	$verifyToken=$_GET['verify'];
	
	if(strlen($verifyToken) > 70){
		die("<center><p style='color: red;'>HOLD DOWN!!, ARE YOU TRYING TO HACK OUR WEBSITE? HAHA DON'T TRY!</p><a href='index'>Click here to back...</a>"); // Some bitch trying to hack the website. //
	}else{

	$conn=$database->openConnection();
	
	$stmt=$conn->prepare('SELECT verifyCode FROM Customers WHERE verifyCode=:verify');
	
	$stmt->bindValue(':verify', $verifyToken);
	$stmt->execute();
	
	if($stmt->rowCount() == 0){

		exit(header("Refresh:0; url=register"));
	}else{
		
		$stmtz=$conn->prepare('UPDATE Customers SET verify="1" WHERE verifyCode=:verify');
		$stmtz->bindValue(':verify', $verifyToken);
		$stmtz->execute();
		
		if($stmtz->rowCount() > 0){
			$_SESSION['verifiedNow'] = true;
			exit(header("Refresh:0; url=login"));
			
		}else{
			
			exit(header("Refresh:0; url=register"));
			
		}
		
		

		
	}
	}
	
}

include 'inc/head.php';
?>




	<div class="about-heading">
		<h2>تسجيل <span>مستخدم</span></h2>
	</div>
	<!-- //about-heading -->
	<div class="registration">
		<div class="container">
			<div class="signin-form profile">
				<h3>تسجيل مستخدم</h3>

				<div class="login-form">
					<form autocomplete="off" onSubmit="return false;">
						<input type="email" id="email" name="signup-email" placeholder="البريد" required="">
						
						<input type="text" id="username" name="signup-username" placeholder="إسم المستخدم" required="">
						<input type="text" id="phonenumber" name="signup-phonenumber" placeholder="رقم الهاتف" required="
						">
						<input type="password" id="signup-password" name="signup-password" placeholder="كلمة السر" required="">
						<input type="password" id="signup-password-confirm" name="signup-password-confirm" placeholder="تأكيد كلمة السر" required="">
						                            <div class="form-group row">
                                <div class="col-12">
									<center><div class="g-recaptcha" data-sitekey="<?=$Config["reCAPTCHA"];?>"></div></center>
                                </div>
                            </div>

						<input type="submit" onClick="register()" value="تسجيل">
					</form>
				</div>
				<p><a href="terms.php" target="_blank">بتسجيلك فأنت توافق على شروط الإستخدام</a></p>
			</div>
		</div>
	</div>
<?php

include 'inc/footer.php';

?>


		<script>
		<?php if(isset($_GET['timeout']) && $_GET['timeout'] == 1){ ?>
		alertify.logPosition("top right");
		alertify.error("تم تسجيل خروجك بنجاح، نظرًأ لعدم تفاعلك سجل مججدا!");
		<?php } ?>
		 function acceptrules(){
			 document.getElementById('signup-terms').setAttribute("checked", "");
		 }
		 function validatePwd(pwd){
			// at least one number, one lowercase and one uppercase letter
			// at least six characters
			var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
			return re.test(pwd);
		 }
		function validateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		}
		function register(){
				var username=document.getElementById("username").value;
				var password=document.getElementById("signup-password").value;
				var phonenumber=document.getElementById("phonenumber").value;
				var email=document.getElementById("email").value;
				if(!validateEmail(email)){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من صحة البريد'
					});
				}else if(!validatePwd(password)){
					toast({
					  type: 'error',
					  title: 'يجب أن تحتوي كلمة المرور على حرف صغير وكبير ورقم على الأقل وأن تكون اكثر من 8 أحرف'
					});
				}else if(username == null || username == "" || phonenumber == "" || phonenumber == null){
					toast({
					  type: 'error',
					  title: 'من فضلك تأكد من المدخلات'
					});
				}else{
					if (grecaptcha === undefined) {
						toast({
						  type: 'error',
						  title: 'عذراً ولاكن التحقق غير موجود'
						});
						setTimeout(function () { location.href = "register.php";}, 3000);
						throw new Error("Empty RECAPTCHA");
					}else if (!grecaptcha.getResponse()) {
						swal({
						  title: "خطأ",
						  text: "من فضلك تحقق من أنك لست روبوت",
						  type: "error"
						});
						throw new Error("Robot Check");
					}
					sendData("reg.php", "email="+email+"&password="+password+"&username="+username+"&phonenumber="+phonenumber+"&reCAPTCHA="+grecaptcha.getResponse())
					.then(function(response){
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
							grecaptcha.reset();
							document.getElementById("username").value = '';
							document.getElementById("signup-password").value = '';
							document.getElementById("signup-password-confirm").value = '';
							document.getElementById("phonenumber").value = '';
							document.getElementById("email").value = '';
							document.location = 'login.php';
						}
					});
				}
		}
</script>
		<script src="https://unpkg.com/sweetalert2@7.21.1/dist/sweetalert2.all.js"></script>
                <script src="js/jquery.validate.min.js"></script>



</body>
</html>
