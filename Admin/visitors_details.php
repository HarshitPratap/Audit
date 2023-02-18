<?php
	$page_title = "Visitors Details";
	include_once 'Includes/header.php';
 ?>
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
					'block': "visitors"
				},
				success: function(resp) {
					var json_data = JSON.parse(resp);
					console.log(json_data[0]);
					var i= 0,outp = "";
					$.each(json_data, function(index, visitor) {
						outp += "<tr><td>"+ ++i +"</td><td>"+ visitor.name +"</td><td>"+ visitor.addr +"</td><td>"+ visitor.dist +"</td><td>"+ visitor.state +"</td><td>"+ visitor.phone +"</td><td>"+ visitor.email +"</td><td>"+ visitor.username +"</td></tr>";
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
	<h2 align="center"><u>Visitors Details</u></h2>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Name</th>
				<th scope="col">Employee Id</th>
				<th scope="col">Designation</th>
				<th scope="col">Base Location</th>
				<th scope="col">Phone</th>
				<th scope="col">Email</th>
				<th scope="col">Username</th>
			</tr>
		</thead>
		<tbody id="out">
		</tbody>
	</table>
</div>
<?php
include_once 'Includes/footer.php'; ?>
