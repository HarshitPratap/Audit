<?php
	$page_title = "Change Password";
	include_once 'Includes/header.php';
?>

 <script>
		$(document).ready(function(){
			$("#cnftoggle").click(function(){
				if($("#cnftogglei" ).hasClass("fa-eye"))
				{
						$("#cnf").attr("type", "text");
						$("#cnftogglei").attr("class", "fas fa-eye-slash");
						return false;
				}
				else if ($("#cnftogglei" ).hasClass("fa-eye-slash")) {
						$("#cnf").attr("type", "password");
						$("#cnftogglei").attr("class", "fas fa-eye");
						return false;
				}
			});
			$("#newptoggle").click(function(){
				if($("#newptogglei" ).hasClass("fa-eye"))
				{
						$("#newp").attr("type", "text");
						$("#newptogglei").attr("class", "fas fa-eye-slash");
						return false;
				}
				else if ($("#newptogglei" ).hasClass("fa-eye-slash")) {
						$("#newp").attr("type", "password");
						$("#newptogglei").attr("class", "fas fa-eye");
						return false;
				}
			});
			$("#cnfnewtoggle").click(function(){
				if($("#cnfnewtogglei" ).hasClass("fa-eye"))
				{
						$("#cnfnew").attr("type", "text");
						$("#cnfnewtogglei").attr("class", "fas fa-eye-slash");
						return false;
				}
				else if ($("#cnfnewtogglei" ).hasClass("fa-eye-slash")) {
						$("#cnfnew").attr("type", "password");
						$("#cnfnewtogglei").attr("class", "fas fa-eye");
						return false;
				}
			});
			$("#frm").submit(function(event){
				event.preventDefault();
				var data = $( this ).serializeArray();
				$.each(data, function(index, val) {
					if(val.value == "")
					{
						 event.trigger('unload');
					}
				 });
				 $.ajax({
					method: 'POST',
					url: 'Controller/Parsechangepass.php',
					data: data,
					success: function(resp) {
						var mess = JSON.parse(resp);
						$("#alert").text(mess.msg);
						$("#alert").addClass(mess.class);
					}
				});
				$("#frm :password").each(function(){
					$(this).val("");
				});
			});
			$("#cnf").blur(function() {
					var pass = $("#cnf").val();
					 $.ajax({
						method: 'POST',
						url: 'data_files.php',
						data: {
							'block': "cnfpass",
							'id': "<?=$_SESSION['loginuser']?>",
							'pass': pass
						},
						success: function(resp) {
							var json_data = JSON.parse(resp);
							 console.log(json_data.msg);
							 $("#outp").text(json_data.msg);
						},
						error: function(err) {
							console.log("error : "+err);
						}
				});
			});
		});
  </script>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
				<div id="alert"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
		<form method="post" action="#" id="frm" enctype="multipart/form-data">
			<fieldset class="border p-2">
				<legend class="w-auto">Change Password</legend>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<label for="cnf">Current Password</label>
							<div class="input-group">
									<input type = "password" name="cnf" id="cnf" class="form-control" placeholder="Current Password" aria-describedby="cnftoggle"/>
									<div class="input-group-append">
							      <span class="input-group-text" id="cnftoggle"><i id="cnftogglei" class="fas fa-eye" ></i></span>
							    </div>
							</div>
						</div>
						<span id="outp" style="color:#D51C00; margin-left:10px;"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<label for="newp">New Password</label>
							<div class="input-group">
									<input type = "password" name="newp" id="newp" class="form-control" placeholder="New Password" aria-describedby="newptoggle"/>
									<div class="input-group-append">
							      <span class="input-group-text" id="newptoggle"><i id="newptogglei" class="fas fa-eye"></i></span>
							    </div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<label for="cnfnew">Confirm Password</label>
							<div class="input-group">
									<input type = "password" name="cnfnew" id="cnfnew" class="form-control" placeholder="Confirm Password" aria-describedby="cnfnewtoggle"/>
									<div class="input-group-append">
							      <span class="input-group-text" id="cnfnewtoggle"><i id="cnfnewtogglei" class="fas fa-eye" ></i></span>
							    </div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12">
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12">
						<input type="submit" value="Change Password" name="submit" class="btn btn-outline-danger btn-block"/>
					</div>
				</div>
			</fieldset>
		</form>
		</div>
	</div>
</div>
<?php
include_once 'Includes/footer.php'; ?>
