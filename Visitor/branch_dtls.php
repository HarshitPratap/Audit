<?php
$page_title = "Branch Details";
include_once 'Includes/header.php';
?>
<script>
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
			'block': "brn_dtls",
			'id': "<?=$_SESSION['loginuserid']?>"
		},
		success: function(resp) {
			var json_data = JSON.parse(resp);
			console.log(json_data[0]);
			var i= 0,outp = "";
			$.each(json_data, function(index, branch) {
				let btn_sts = '';
				if (branch.updated == 1) {
					btn_sts = "disabled = \"disabled\"";
				}else if (branch.updated == 0) {
					btn_sts = '';
				}
				outp += "<tr><td>"+ ++i +"</td><td>"+ branch.brn_code +"</td><td>"+ branch.brn_name +"</td><td>"+ branch.brn_for_date +"</td><td>"+ branch.reg_name +"</td><td>"+ branch.brn_mng +"</td><td>"+ branch.area_mng +"</td><td>"+ branch.reg_mng +"</td><td><a href=\"update_branch_dtls.php?pg="+branch.id+"\"><button "+ btn_sts +" type=\"button\"  class=\"btn btn-outline-primary btn-sm\">UPDATE</button></a></td></tr>";
			});
			$("#out").append(outp);
		},
		error: function(err) {
			console.log("error : "+err);
		}
	});
});

</script>
<div class="container-fluid">
	<h2 align="center"><u>Branches Details</u></h2>
	<table class="table table-striped table-bordered">
		<thead class="thead-primary">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Branch Code</th>
				<th scope="col">Branch Name</th>
				<th scope="col">Branch Formation Date</th>
				<th scope="col">Region Name</th>
				<th scope="col">Branch Manager</th>
				<th scope="col">Area Manager</th>
				<th scope="col">Regional Manager</th>
				<!-- <th scope="col">Auditor Name(s)</th> -->
				<th scope="col">Update Details</th>
			</tr>
		</thead>
		<tbody id="out">

		</tbody>
	</table>

</div>
<?php
include_once 'Includes/footer.php'; ?>
