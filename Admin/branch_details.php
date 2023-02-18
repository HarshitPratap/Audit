<?php
$page_title = "Branch Details";
include_once 'Includes/header.php';
?>
<script type = "application/javascript">
			$(document).ready(function(){
				ajaxLoader.init({
					'loader': 'pulser',
					'theme' : 'love',
					'size' : 75,
				});
			// 	$('#datatable').DataTable( {
			// 	 "ajax":{
			// 				url: "data_table.php",
			// 				datasrc: "data"
			// 			},
			// 			columns: [
			// 					{ data: "sr" },
		  //           { data: "brn_code" },
		  //           { data: "brn_name" },
		  //           { data: "brn_for_date" },
		  //           { data: "reg_name" },
		  //           { data: "brn_mng" },
		  //           { data: "area_mng" },
			// 					{ data: "reg_mng" },
			// 					{ data: "brn_size" },
		  //       ]
			// 		});
			// });
				 $.ajax({
					method: 'POST',
					url: 'data_files.php',
					data: {
						'block': "brn_dtls"
					},
					success: function(resp) {
						var json_data = JSON.parse(resp);
						//console.log(json_data[0]);
						var i= 0,outp = "";
						$.each(json_data, function(index, branch) {
							outp += "<tr><td>"+ ++i +"</td><td>"+ branch.brn_code +"</td><td>"+ branch.brn_name+"</td><td>"+ branch.brn_for_date +"</td><td>"+ branch.reg_name +"</td><td>"+ branch.brn_mng +"</td><td>"+ branch.area_mng +"</td><td>"+ branch.reg_mng +"</td></tr>";
						});
						$("#out").append(outp);
					},
					error: function(err) {
						console.log("error : "+err);
					}
				});
			});
				 // $('.table').attr('id', "mytable");
				 // $('#mytable').dataTable();
				// $(window).on('wheel', function(event) {
				//   if (event.originalEvent.deltaY > 0) {
				//     console.log('down');
				//   } else {
				//    console.log('up');
				//   }
				// });
		//	});
  </script>
<div class="container-fluid">
	<h2 align="center"><u>Branches Details</u></h2>
	<table class="table table-bordered table-hover table-reponsive" id="datatable">
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
				<!-- <th scope="col">Auditor Name(s)</th> -->
				<!-- <th scope="col">Branch Size</th> -->
				<!-- <th scope="col">Map To</th> -->
			</tr>
		</thead>
		<tbody id="out">

		</tbody>
	</table>
</div>
<?php
	include_once 'Includes/footer.php';
 ?>
