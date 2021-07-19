<?php
$pageName="staffinfo";
ob_start();
require_once("inc/header.php");
if(rankPermission($_SESSION['staffId:wse6'],3,true) OR rankPermission($_SESSION['staffId:wse6'],1,true)){
	exit(header('Location: ./home'));
}
?>
            <main id="main-container">
				<div class="content">
					<div class="row text-center">
						<div class="col-xl-2"></div>
						<div class="col-xl-8">
							<div class="block block-themed">
								<div class="block-header bg-gd-dusk">
									<h3 class="block-title" id="title">الإدارة &mdash; معلومات الإدارة</h3>
								</div>

								<div class="block-content" id="content" style="height: 350px;">
									<form method="post" onsubmit="return false;" autocomplete="off">
											<div class="form-group">
													<div class="form-material">
														<select class="js-select2 form-control" id="staffSelect" style="width: 100%;" data-placeholder="إختر العضو" onchange="_getInfoStaff()">
															<option><?php 
															
															$stmt=$conn->query('SELECT id,username FROM Customers WHERE isStaff=1');

															foreach($stmt as $row){
																$username=htmlspecialchars($row["username"]);

																echo '<option value="'.$row['id'].'">'.$username.'</option>';
															}

															?></option>
														</select><br/><br/>
														<div id="infoHere"></div>
													</div>
												</div>
												
																					
									</form>
							
									
									<br/>
									
								</div>
							</div>
						</div>
					</div>
		</div>
	</main>
	

	<script>
		
		function _getInfoStaff() {
			var staffSelected = document.getElementById('staffSelect').value;
			if(typeof staffSelect !== 'undefined') {
				sendData("staffInfo", "staff="+staffSelected,'GET')
					.then(function(response){
						if(typeof response.done !== 'undefined' && response.done == true){ 
							document.getElementById("infoHere").innerHTML="<p>معلومات &mdash; "+response.name+"</p><br/><span>عدد مرات الوساطة: 0</span><br/><span><div class="font-size-sm text-muted"></div>"+response.earnings+" $ </div></span>";

						} else {

							swal({
								title: response.t,
								text: response.m,
								type: response.tp,
								confirmButtonText: response.b
							});

						}
				});
			}
		}


	</script>

<?php require_once("inc/footer.php"); ?>