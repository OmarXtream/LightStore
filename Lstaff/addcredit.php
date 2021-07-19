<?php
ob_start();
$pageName="addcredit";
require_once("inc/header.php");
if(!rankPermission($_SESSION['staffId:light'],5,true)){
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
									<h3 class="block-title" id="title">الإدارة &mdash; إضافة الرصيد</h3>
								</div>

								<div class="block-content" id="content" style="height: 350px;">
									<form method="post" onsubmit="return false;" autocomplete="off">
											<div class="form-group">
													<div class="form-material">
														<select class="js-select2 form-control" id="userSelected" style="width: 100%;" data-placeholder="إختر العضو" onchange="chooseEvent()">
															<option><?php 
															$conn = $database->openConnection();
															$stmt=$conn->query('SELECT username,id,Credits FROM Customers ORDER BY id ASC');

															foreach($stmt as $row){
																$username=htmlspecialchars($row["username"]);

																echo '<option value="'.$row['id'].'">'.$username.' » '.$row['Credits'].'$</option>';
															}

															?></option>
														</select><br/><br/>
														<div id="hiddenCredit"></div>
													</div>
												</div>
												
																					
									</form>
									
									<div class="col-12"><br/><br/><br/>
										<button class="btn btn-alt-primary" onClick="_addCredit()"><i class="fa fa-arrow-right mr-5"></i> إضافة الرصيد </button>
									</div>
									
									<br/>
									
								</div>
							</div>
						</div>
					</div>
		</div>
	</main>
	

	<script>


		 function chooseEvent () {
			document.getElementById('hiddenCredit').innerHTML='<div id="hiddenCredit"><label>قم بإدخال المبلغ المراد إضافته بالإسفل.</label>		<div class="form-material form-material-primary"><input class="form-control" id="balance" placeholder="كمثال: 100" type="text"><label for="balance">المبلغ:</label></div></div>';
		 }

		function _addCredit() {

			var balanceToAdd=document.getElementById("balance").value;
			var selectedUser=document.getElementById("userSelected").value;

			if(typeof balanceToAdd !== 'undefined' && typeof selectedUser !== 'undefined'){
				sendData("addbalance", "action=add&client="+selectedUser+"&balance="+balanceToAdd,'GET')
					.then(function(response){
						swal({
								  title: response.t,
								  text: response.m,
								  type: response.tp,
								  confirmButtonText: response.b
						});
						if(typeof response.done != 'undefined' && response.done){
							var x = document.getElementById("userSelected").selectedIndex;
							var tochange= document.getElementsByTagName("option")[x];
							tochange.text = response.name + ' » ' + response.balance + '$';
						}
				});
			}
		}


	</script>

<?php require_once("inc/footer.php"); ?>