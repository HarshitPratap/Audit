<?php
$page_title = "Added Questions";
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
						'block': "getcato"
					},
					success: function(resp) {
						var json_data = JSON.parse(resp);
						var i = 1, outp = "";
            outp += "<option value=\"0\">Select Catogory</option>";
						$.each(json_data, function(index, cato) {
							outp += "<option value=\""+ cato.id +"\">"+ cato.catog +"</option>";
						});
						$("#sid").html(outp);
					},
					error: function(err) {
						console.log("error : "+err);
					}
				});
        $("#sid").change(function() {
          var val = $(this).val();
          $.ajax({
          method: 'POST',
          url: 'data_files.php',
          data: {
            'block': "getsubcato",
            'cato': val
          },
          success: function(resp) {
            var json_data = JSON.parse(resp);
            var i = 1, outp = "";
            outp += "<option value=\"0\">Select Sub-Catogory</option>";
            $.each(json_data, function(index, cato) {
              outp += "<option value=\""+ cato.id +"\">"+ cato.subcatog +"</option>";
            });
            $("#sidsub").html(outp);
          },
          error: function(err) {
            console.log("error : "+err);
          }
        });
			});
      function fetch_data(sid, sidsub) {
          $.ajax({
          method: 'POST',
          url: 'data_files.php',
          data: {
            'block': "getques",
            'cato': sid,
            'subcato': sidsub
          },
          success: function(resp) {
            console.log(resp);
            var json_data = JSON.parse(resp);
            var i = 1, outp = "";
            $.each(json_data, function(index, ques) {
              outp += "<tr><td>"+ i++ +"</td><td>"+ ques.ques +"</td><td>"+ ques.ques_serial +"</td><td>"+ ques.weightage +"</td><td>"+ ques.score +"</td><td width=\"8%\"><a href=\"edit_ques.php?pg="+ ques.id +"\" title=\"Edit\"><i style=\"font-size:30px;\" class=\"fa fa-edit myeditbtn\"></i></a><i title=\"Delete\" style=\"font-size:30px;\" class=\"fa fa-trash mybtn\" id=\""+ ques.id +"\"></i></td></tr>";
            });
            $("#out").html(outp);
          },
          error: function(err) {
            console.log("error : "+err);
          }
        });
      }
    $("#sidsub").change(function() {
        var val = $("#sid").val();
        var val1 = $("#sidsub").val();
        fetch_data(val,val1);
    });
    $(document).on('click','.mybtn',function() {
      //alert("hii");
      let id = $(this).attr('id');
      let sid = $("#sid").val();
      let subsid = $("#sidsub").val();
      if(confirm("Are you sure to delete this question?"))
      {
          $.ajax({
            method: 'POST',
            url: 'data_files.php',
            data:{
              'block': "delete_ques",
              'id': id
            },
            success: function(resp){
              alert(resp);
              fetch_data(sid,subsid);
            }
          });
      }
      else
      {
        return false;
      }
    });
    });
  </script>
<div class="container-fluid">
	<h2 align="center"><u>Added Question Details</u></h2>
	<form method="post" action="#" enctype="multipart/form-data">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12">
				<div class="form-group">
					<label for="sid">Select Catogory</label>
					<select name="sid" id="sid" class="form-control form-control-sm">
						<option value="0">Select Catogory</option>
					</select>
				</div>
			</div>
      <div class="col-lg-3 col-md-3 col-sm-12">
				<div class="form-group">
					<label for="sidsub">Select Sub-Catogory</label>
					<select name="sidsub" id="sidsub" class="form-control form-control-sm">
						<option value="0">Select Sub-Catogory</option>
					</select>
				</div>
			</div>
		</div>
	</form>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Question</th>
        <th scope="col">Serial No.</th>
				<th scope="col">Weightage</th>
				<th scope="col">Score</th>
        <th scope="col">Edit | Update | Delete</th>
			</tr>
		</thead>
		<tbody id="out">
		</tbody>
	</table>
</div>
<?php
  include_once 'Includes/footer.php';
 ?>
