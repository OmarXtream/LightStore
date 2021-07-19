<?php
﻿require_once("../../inc/config.php");

$verifyCode=$_SESSION["verifyCode"];
$name=$_SESSION['name'];

if(isset($_SESSION['account']) && isset($_SESSION['name'])){
	
session_destroy();?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>LightStore Email</title>
  
</head>

<body dir="rtl">

                                        <p>مرحباً ، <?=$name;?></p>
                                        <p>يرجى تأكيد عنوان بريدك الإلكتروني ، لتنشيط حسابك. إذا تلقيت هذا عن طريق الخطأ أو لم تكن تتوقعه ، فيرجى تجاهل هذا البريد الإلكتروني.</p>
                                        <p><a href="https://www.<? $Config['domain'];?>/register?verify=<?=$verifyCode;?>" target="_blank" rel="noopener noreferrer" style="display:block; text-decoration:none; text-align:center; border-radius:5px; padding:16px 20px; color:rgb(255,255,255); max-width:200px; line-height:16px; font-size:14px; background-color:rgb(0,176,116); margin:0 auto">تأكيد بريدي الإلكتروني</a></p>
  
  

</body>

</html><?php } ?>
