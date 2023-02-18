<?php
$page_title = "Map Branch";
include_once 'Includes/header.php';
include_once 'Controller/Parsemapbranch.php';
	?>
<script type="application/javascript">
	function funchk()
	{
		if(document.getElementById('siv').value == '0')
		{
			alert("Please Select Visitor.");
			document.getElementById('siv').style.borderColor="#D51C22";
			document.getElementById('siv').focus();
			return false;
		}
		else
		{
			document.getElementById("siv").style.borderColor='#999999';
		}
		if(!document.getElementById('Check').checked)
		{
			alert("Please Select Branch.");
			return false;
		}
	}
</script>
<script type = "application/javascript">
		$(document).ready(function(){
			ajaxLoader.init({
				'loader': 'pulser',
				'theme' : 'love',
				'size' : 75,
			});
			 $.ajax({
				method: 'POST',
				url: 'data_files.php',
				data: {
					'block': "mapping"
				},
				success: function(resp) {
					var json_data = JSON.parse(resp);
					console.log(json_data.visitors);
					var i= 0,outp = "",opt = "";
					$.each(json_data.visitors, function(index, visitor) {
						opt += "<option value=\""+ visitor.username +"\">" + visitor.username + " (" + visitor.name + ")</option>";
					});
					$.each(json_data.branches, function(index, branch) {
						outp += "<tr><td><div class=\"form-check\"><input type=\"checkbox\" class=\"form-check-input chk\" name=\"users[]\" value=\""+branch.brn_code+"\" id=\"Check\"/><label class=\"form-check-label\" for=\"Check\">"+ ++i +"</label></div></td><td>"+ branch.brn_code +"</td><td>"+ branch.brn_name +"</td><td>"+ branch.brn_for_date +"</td><td>"+ branch.reg_name +"</td><td>"+ branch.brn_mng +"</td><td>"+ branch.area_mng +"</td><td>"+ branch.reg_mng +"</td></tr>";
					});
					$("#siv").append(opt);
					$("#tabledata").append(outp);
				},
				error: function(err) {
					console.log("error : "+err);
				}
			});
		});
</script>
<style>
#btnn{
margin-top:20px;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<h2 align="center"><u>Map Branch</u></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<form action="#" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<label for="siv">Select Visitor</label>
							<select name="siv" id="siv" class="form-control form-control-sm">
								<option value="0">Select Visitor</option>
							</select>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6">
						<div class="form-group">
							<label for="btnn"></label>
							<input type="submit" name="submit" value="Map" id="btnn" class="btn btn-block btn-outline-success" onclick="return funchk();"/>
						</div>
					</div>
				</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th scope="col">Branch Code</th>
								<th scope="col">Branch Name</th>
								<th scope="col">Branch Formation Date</th>
								<th scope="col">Region Name</th>
								<th scope="col">Branch Manager</th>
								<th scope="col">Area Manager</th>
								<th scope="col">Regional Manager</th>
							</tr>
						</thead>
						<tbody id="tabledata">

						</tbody>
					</table>
				</div>
			</div>
			<div class="row">

			</div>
	     </form>
	  </div>
   </div>
 </div>
<?php
include_once 'Includes/footer.php'; ?>
