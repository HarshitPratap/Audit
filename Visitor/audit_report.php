<?php
	$page_title = "Audit | Visit | Visitors";
	include_once 'Includes/header.php';
  ?>
	<style>
		table thead tr{
			position: sticky;
			background-color: #007bff;
		  z-index: 10000;
		  top: 64px;
		}
	</style>
  <script type="text/javascript">
    $(document).ready(function (){
      $.ajax({
        method:'POST',
        url:'data_files.php',
        data:{
          'block' : 'get_branch'
        },
        success:function(resp){
          var data = JSON.parse(resp);
          var out = "<option value=\"0\">Select Branch</option>";
          $.each(data, function(index, brnch) {
            out += "<option value=\"" + brnch.brn_code + "\">" + brnch.brn_code +" ( "+ brnch.brn_name + " )</option>";
          });
          $('#branch_id').html(out);
        },
        error:function(err){
          console.log("ERROR : " + err);
        }
      });
      $("#audit_report_form").on('submit',function(e){
        e.preventDefault();
        let form_data = $(this).serializeArray();
        $.ajax({
            method:'POST',
            url:'audit_report_datafiles.php',
            data:form_data,
            success:function(resp){
              //var data = JSON.parse(resp);
							$("#out").html(resp);
            },
            error:function(err){
              console.log("ERROR : " + err);
            }
        });
      });
			/*$("#export_button").click(function (e) {
				$("#table_data").table2excel({
            filename: "Employees.xls"
        });
			});*/
    });
  </script>
  <div class="container">
    <div class="jumbotron">
        <h2 align="center">
          Audit Report
        </h2>
    </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form enctype="application/x-www-form-urlencoded" action="#" method="post" id="audit_report_form">
            <div class="row">
              <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="branch_id">Select Branch</label>
                      <select id="branch_id" name="branch_id" class="form-control">
                      </select>
                </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12">
                <button class="btn btn-outline-primary mt-lg-4 mt-md-4 mt-sm-0" type="submit" name="view_report" id="view_report">View Report</button>
							</div>
          </div>
          </form>
        </div>
      </div>
			<!-- <div class="row">
				<div class="col-lg-3 col-md-3 col-sm-4">
						 <button class="btn btn-outline-success btn-sm float-left" id="export_button">Export To Excel</button>
						<input type="button" class="btn btn-outline-success btn-sm float-left" id="export_button" value="Export"/>
				</div>
			</div> -->
		<div class="table-responsive">
      <table class="table table-bordered table-striped" id="table_data">
          <thead class="bg-primary">
						<tr>
              <th scope="col">#</th>
              <th scope="col">Question</th>
              <th scope="col">Criteria</th>
              <th scope="col">Weightage</th>
              <th scope="col">Score</th>
              <th scope="col">Comment</th>
						</tr>
          </thead>
          <tbody id="out">

          </tbody>
      </table>
		</div>
  </div>

  <?php
  include_once 'Includes/footer.php'; ?>
