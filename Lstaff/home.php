<?php $pageName="home"; require_once("inc/header.php"); ?>

            <main id="main-container">
                <div class="content">
				<div class="row invisible" data-toggle="appear">
									<div class="col-6 col-xl-3">
						<a class="block block-link-shadow text-right" href="javascript:void(0)">
							<div class="block-content block-content-full clearfix">
								<div class="float-left mt-10 d-none d-sm-block">
									<i class="si si-users fa-3x text-body-bg-dark"></i>
								</div>
								<div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="<?=$homeInfo['StaffCount'];?>"><?=$homeInfo['StaffCount'];?></div>
								<div class="font-size-sm font-w600 text-uppercase text-muted">عدد الطاقم الإداري</div>
							</div>
						</a>
					</div>
					
					<div class="col-6 col-xl-3">
						<a class="block block-link-shadow text-right" href="javascript:void(0)">
							<div class="block-content block-content-full clearfix">
								<div class="float-left mt-10 d-none d-sm-block">
									<i class="si si-users fa-3x text-body-bg-dark"></i>
								</div>
								<div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="<?=$homeInfo['CActivated'];?>"><?=$homeInfo['CActivated'];?></div>
    <div class="font-size-sm font-w600 text-uppercase text-muted">اعضاء الموقع</div>
</div>
						</a>
					</div>
					
					<div class="col-6 col-xl-3">
						<a class="block block-link-shadow text-right" href="javascript:void(0)">
							<div class="block-content block-content-full clearfix">
								<div class="float-left mt-10 d-none d-sm-block">
									<i class="si si-wallet fa-3x text-body-bg-dark"></i>
								</div>
								<div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="<?=$homeInfo['MCompleted'];?>"><?=$homeInfo['MCompleted'];?></div>
<div class="font-size-sm font-w600 text-uppercase text-muted">طلبات  مكتملة</div>							</div>
						</a>
					</div>
					
					<div class="col-6 col-xl-3">
						<a class="block block-link-shadow text-right" href="javascript:void(0)">
							<div class="block-content block-content-full clearfix">
								<div class="float-left mt-10 d-none d-sm-block">
									<i class="si si-wallet fa-3x text-body-bg-dark"></i>
								</div>
								<div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="<?=$homeInfo['MNotCompleted'];?>"><?=$homeInfo['MNotCompleted'];?></div>
<div class="font-size-sm font-w600 text-uppercase text-muted">طلبات  غير مكتملة</div>							</div>
						</a>
					</div>

				</div>
	
				<div class="block block-rounded bg-gd-emerald">
				<div class="block-content bg-white-op-5">
				<div class="py-30 text-center">
				<h1 class="font-w700 text-white mb-10">اهلاً بعودتك</h1>
                 <h2 class="h4 font-w400 text-white-op">تستطيع عن طريقة لوحة التحكم هذه التحكم بموقعك الجميل.</h2>
                  </div>
				</div>
				</div>
				<?php
				if(rankPermission($_SESSION['staffId:light'],5,true)){
					$row = $conn->query('SELECT * FROM sitesettings')->fetch();
					$closeSiteStatus = $row['closeSite'] == 1 ? 'فتح': 'إغلاق';
					$closeApplyStatus = $row['closeapply'] == 1 ? 'فتح': 'إغلاق';
					$closePaypalStatus = $row['closepaypal'] == 1 ? 'فتح': 'إغلاق';

					?>
				<div class="row">
					<div class="col-md-6">
						<a class="block block-link-shadow" onclick="changeStatus(1)" href="#">
							<div class="block-content text-center">
								<div class="py-20">
									<p class="mb-10">
										<i class="si si-globe font-size-h1 text-danger"></i>
									</p>
									<p class="font-size-lg"><span id="status1"><?=$closeSiteStatus?></span> الموقع</p><p>
								</p></div>
							</div>
						</a>
					</div>

					<div class="col-md-6">
						<a class="block block-link-shadow" onclick="changeStatus(2)" href="#">
							<div class="block-content text-center">
								<div class="py-20">
									<p class="mb-10">
										<i class="si si-user-follow font-size-h1 text-warning"></i>
									</p>
<p class="font-size-lg"><span id="status2"><?=$closeApplyStatus?></span> التقديم </p><p>
    								</p></div>
							</div>
						</a>
					</div>

					<div class="col-md-4">
						<a class="block block-link-shadow" onclick="changeStatus(3)" href="#">
							<div class="block-content text-center">
								<div class="py-20">
									<p class="mb-10">
										<i class="si si-credit-card font-size-h1 text-info"></i>
									</p>
									<p class="font-size-lg"><span id="status3"><?=$closePaypalStatus?></span> الدفع بـ paypal</p><p>
								</p></div>
							</div>
						</a>
					</div>


				</div>
				</div>

					<?php
				}
				?>
                </div>
            </main>
<script>
function changeStatus(type){
	sendData("sitesettings.php", "sitesettings="+type)
	.then(function(response){
		swal({
			title: response.t, 
			text: response.m,
			type: response.tp,
			showConfirmButton: response.b,
			confirmButtonText: 'موافق'
		});
		if(response.tp == 'success'){
			document.getElementById('status'+type).innerText = response.newStatus;
		}
	});
}
</script>
<?php require_once("inc/footer.php"); ?>