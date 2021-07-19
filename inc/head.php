<?php 
if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

session_start();
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en" >
            <style id="css-main">@font-face{font-family:myFirstFont;src:url(hcdd.ttf)/*tpa=*/ format('truetype');unicode-range:U +0600-06EF , U +06FA-0903}@font-face{font-family:dinar2;src:url(ge-dinar2.ttf)/*tpa=*/ format("truetype");font-weight:normal;font-style:normal;unicode-range:U +0600-06EF , U +06FA-0903}@font-face{font-family:h-tunisia;src:url(H-Tunisia.ttf)/*tpa=*/ format('truetype');unicode-range:U +0600-06EF , U +06FA-0903}@font-face{font-family:h-tunisia;src:url(H-Tunisia-B.ttf)/*tpa=*/ format('truetype');font-weight:bold;unicode-range:U +0600-06EF , U +06FA-0903}@font-face{font-family:h-pro;src:url(H-Promoter.ttf)/*tpa=*/ format('truetype');unicode-range:U +0600-06EF , U +06FA-0903}@font-face{font-family:h-pro;src:url(H-Promoter-M.ttf)/*tpa=*/ format('truetype');font-weight:bold;unicode-range:U +0600-06EF , U +06FA-0903}div h1,div h2,div h3,div h4,div p,div.h1,div.h2,div.h3,div.h3{font-family:'Open Sans',dinar2}div{font-family:myFirstFont,'Open Sans'}font1{font-family:myFirstFont,'Open Sans'!important}font2{font-family:dinar2,'Open Sans'!important}.progamer{font-family:h-pro!important;font-weight:bold!important}</style>

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?=$Config["title"];?></title>
        <meta name="description" content="<?=$Config["description"];?>">
        <meta name="author" content="Masafat">
		<meta name="token" content="<?php if(isset($_SESSION['_token'])) { echo $_SESSION['_token']; } ?>">
        <meta property="og:title" content="<?=$Config["title"];?>">
        <meta property="og:site_name" content="<?=$Config["title"];?>">
        <meta property="og:description" content="<?=$Config["description"];?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="<?=$Config["icon"];?>">
        <link rel="shortcut icon" href="<?=$Config["icon"];?>">

<link rel="icon" href="<?=$Config["icon"];?>">
<link rel="apple-touch-icon-precomposed" href="<?=$Config["icon"];?>">


		<noscript><meta HTTP-EQUIV="refresh" content=0; url="javascriptErr.php"></noscript>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!--// bootstrap-css -->
<!-- css -->
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<!--// css -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet">

<!-- //font-awesome icons -->
<!-- font -->
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300' rel='stylesheet' type='text/css'>



<!-- //font -->

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/SmoothScroll.min.js"></script>
<script src="js/sweetalert.js"></script>
<script src="js/script.js"></script>
<script src='https://www.google.com/recaptcha/api.js?hl=ar'></script>
<? if(!isset($nostyle)){ ?>
		<link rel="stylesheet" href="css/ion.rangeSlider.min.css">
		<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.min.css">

        <link rel="stylesheet" id="css-main" href="css/codebase.min-2.1.css">
<? } ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<link href='css/immersive-slider.css' rel='stylesheet' type='text/css'>
<!-- pricing -->
<link rel="stylesheet" href="css/jquery.flipster.css">
<!-- //pricing -->
          <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
            <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
            <link rel="stylesheet" href="  https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

<script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>

</head>
<body>
	<div class="header-top">
		<div class="container">
			<div class="w3layouts-address">
				<ul>
				    	<? if(isset($_SESSION['memberId:light'])){ ?>
                    
                    	<li><i class="fa fa-user" aria-hidden="true"></i> <? echo htmlspecialchars($_SESSION['clientnickname']); ?> </li> 

                    
                    
                    <?}else{ ?>
					<li><i class="fa fa-mobile" aria-hidden="true"></i> 522401144 971+ </li> &nbsp;&nbsp;&nbsp;&nbsp;
					<li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:info@example.com"> mail@example.com</a></li>
					<? } ?>
				</ul>
			</div>
			<div class="agileinfo-social-grids">
				<ul>
					<li><a href="#"><i class="fa fa-twitter"></i></a></li>
					<li><a href="#"><i class="fa fa-instagram"></i></a></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<!-- header -->
	<div class="header">
			<div class="container">
				<nav class="navbar navbar-default">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">الصفحات</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
						<div class="w3layouts-logo">
							<h2><a href="index.html">Arma 3 <span>Light store</span></a></h2>
						</div>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
						<nav>
							<ul class="nav navbar-nav">
				    	<? if(isset($_SESSION['memberId:light'])){ ?>
							    	    
							    		<li><a href="logout.php" class="hvr-sweep-to-bottom">تسجيل الخروج</a></li>
							 <li <?php if($pageName == "own"){ echo 'class="active"'; } ?>><a href="own" class="hvr-sweep-to-bottom">مشترياتي</a></li>
							 <li <?php if($pageName == "payment"){ echo 'class="active"'; } ?>><a href="payment" class="hvr-sweep-to-bottom">شحن الرصيد</a></li>

							    <?	} ?>
								<li <?php if($pageName == "tickets"){ echo 'class="active"'; } ?>><a href="tickets" class="hvr-sweep-to-bottom">التذاكر</a></li>
								<li <?php if($pageName == "products"){ echo 'class="active"'; } ?>><a href="products" class="hvr-sweep-to-bottom">المنتجات</a></li
								>
								<li <?php if($pageName == "home"){ echo 'class="active"'; } ?>><a href="index" class="hvr-sweep-to-bottom">الرئيسية</a></li>
							</ul>
						</nav>
					</div>
					<!-- /.navbar-collapse -->
				</nav>
			</div>
	</div>
	<!-- //header -->
