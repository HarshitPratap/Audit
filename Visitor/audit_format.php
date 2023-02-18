<?php
	$page_title = "Audit | Visit | Visitors";
	include_once 'Includes/header.php';
	if (isset($_POST['start_audit']) && isset($_POST['brnchid'])) {
		// code for settings session for next steps
		$result = explode('|',$_POST['brnchid']);
		$_SESSION['brnchid'] = $result[0];
		$_SESSION['brnchcode'] = $result[1];
	}
	if(isset($_POST['change_branch'])){
		unset($_SESSION['brnchid']);
		unset($_SESSION['brnchcode']);
	}
?>
<?php
  if(!isset($_SESSION['brnchid']))
  {
  ?>
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
            out += "<option value=\"" + brnch.id + "|" + brnch.brn_code + "\">" + brnch.brn_code +" ( "+ brnch.brn_name + " )</option>";
          });
          $('#brnchid').html(out);
        },
        error:function(err){
          console.log("ERROR : " + err);
        }
      });
    });
  </script>
    <div class="container">

      <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="jumbotron">
              <h2 align="center" style="color:#d51c22;">Audit</h2>
							<p align="center"><small>Select branch for further process</small></p>
            </div>
            <form method="post" action="#" enctype="application/x-www-form-urlencoded">
                <div class="row">
                  <div class="col-lg-4 col-md-4">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                      <div class="form-group">
                          <label for="brnchid">Select Branch</label>
                          <select class="form-control" name="brnchid" id="brnchid">

                          </select>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                      <div class="form-group float-right">
                          <input type="submit" class="btn btn-outline-success btn-sm" value="Start Audit" name="start_audit" />
                      </div>
                  </div>
                </div>
            </form>
          </div>
      </div>
    </div>
  <?php
} else {
?>
<script type = "application/javascript">
			$(document).ready(function() {
				$("#hr_anex_button").prop('disabled', true);
				$("#hr_anex_button").prop('hidden', true);
				$("#submit_btn").prop('disabled',true);
				$("#next_btn").prop('disabled',true);
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
            $("#answered_ques").html("");
					if ($(this).val() == 6) {
						$("#hr_anex_button").prop('disabled', false);
						$("#hr_anex_button").prop('hidden', false);
					}else {
						$("#hr_anex_button").prop('disabled', true);
						$("#hr_anex_button").prop('hidden', true);
					}
					$("#ques").val("");
					$("#quesid").val("");
					$("#quesser").val("");
					$("#submit_btn").prop('disabled',true);
					$("#next_btn").prop('disabled',true);
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
			const fetch_answered_ques = (sid, sidsub) => {
				$.ajax({
						method:'POST',
						url: 'data_files.php',
						data:{
							'block': "get_answered_ques",
							'branch': "<?=$_SESSION['brnchcode'];?>",
							'cato': sid,
							'subcato': sidsub
						},
						success: function(resp){
							json_data = JSON.parse(resp);
							//console.log(json_data);
							let outp = "";
							if (json_data.length > 1) {
								$.each(json_data, function(index, answered_ques_id) {
									if (index == json_data.length - 1) {
										return false;
									}
									outp += answered_ques_id.ques_ser+", ";
								});
								outp = outp.substring(0, (outp.length-2));
							}else {
								outp = "None";
							}
							$("#answered_ques").html(outp+ " Of " + json_data[json_data.length-1].tot_ques);
						},
						error: function(err) {
							console.log("error : "+ err);
						}
				});
			};
      const fetch_data = (sid, sidsub) => {
          let json_data = null;
          $.ajax({
          method: 'POST',
          url: 'data_files.php',
          data: {
            'block': "getques",
            'cato': sid,
            'subcato': sidsub
          },
					async: false,
          success: function(resp) {
            json_data = JSON.parse(resp);
          },
          error: function(err) {
            console.log("error : "+err);
          }
        });
        return json_data;
      };
			function submit_data (val,val1) {
					let form_data = $(val).serializeArray();
					$.ajax({
					method: 'POST',
					url: 'Controller/Parseaudit_format.php',
					data: form_data,
					success: function(resp) {
						let jsdata = JSON.parse(resp);
						//console.log(resp);
						if(jsdata.status == "Success"){
							alert(jsdata.msg);
							$(val1).modal("hide");
							//$(".myansmodal").fadeIn("slow");
							//$("#answ").prop('disabled',true);
							//$("#comment").prop('disabled',true);
							$("#answ").html("<option value=\"Yes\">Yes</option><option value=\"N/A\">N/A</option>");
							$("#answ").val("Yes");
							$("#avg").prop('disabled',false);
							return true;
						}else if (jsdata.status == "Fail") {
							alert(jsdata.msg);
							return false;
						}
					},
					error: function(err) {
						console.log("Error :"+ err);
					}
				});
			}

			var count = 0;
			var data = null;
      $("#sidsub").change(function() {
					count = 0;
          var val = $("#sid").val();
          var val1 = $("#sidsub").val();

					if(val != 0 && val1 != 0)
					{
						data = fetch_data(val,val1);
						fetch_answered_ques(val,val1);
					}
					if(val == 4 && val1 == 10)
					{
							$("#avg_div, #cmnt_div").css("display","block");
							$("#avg, #cmnt").prop('disabled', false);
							$("#risk_input1_div, #risk_input2_div").css("display","none");
							$("#risk_input1, #risk_input2").prop('disabled', true);
						//	$(".myansmodal").fadeOut("slow");
							$("#cgt").modal("show");
					}
				  else	if(val == 4 && val1 == 11)
					{
							$("#avg_div, #cmnt_div").css("display","block");
							$("#avg, #cmnt").prop('disabled', false);
							$("#risk_input1_div, #risk_input2_div").css("display","none");
							$("#risk_input1, #risk_input2").prop('disabled', true);
						//	$(".myansmodal").fadeOut("slow");
							$("#grt").modal("show");
					}
					else if(val == 4 && val1 == 12)
					{
							$("#avg_div, #cmnt_div").css("display","block");
							$("#avg, #cmnt").prop('disabled', false);
							$("#risk_input1_div, #risk_input2_div").css("display","none");
							$("#risk_input1, #risk_input2").prop('disabled', true);
						//	$(".myansmodal").fadeOut("slow");
							$("#loan").modal("show");
					}
					else if(val == 4 && val1 == 13)
					{
							$("#avg_div, #cmnt_div").css("display","block");
							$("#avg, #cmnt").prop('disabled', false);
							$("#risk_input1_div, #risk_input2_div").css("display","none");
							$("#risk_input1, #risk_input2").prop('disabled', true);
							//$(".myansmodal").fadeOut("slow");
							$("#center_meet").modal("show");
					}
					else if ((val == 7 && val1 == 19) || (val == 7 && val1 == 20) || (val == 7 && val1 == 21)) {
							$("#avg_div, #cmnt_div").css("display","none");
							$("#avg, #cmnt").prop('disabled', true);
							$("#risk_input1_div, #risk_input2_div").css("display","block");
							$("#risk_input1, #risk_input2").prop('disabled', false);
							$("#risk_input1, #risk_input2").val("");
							if (val1 == 19) {
								$("#risk_input1_label").html("Total No. of Clients end of the last quarter");
								$("#risk_input2_label").html("Total No. of Clients end of the audit period/quarter");
							}else if (val1 == 20) {
								$("#risk_input1_label").html("Total No. of  Audit findings submittted including no. of audit report");
								$("#risk_input2_label").html("Total No. of satisfactory audit compliance recevied&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
							}else {
								$("#risk_input1_label").html("Total No. of Staff Involved");
								$("#risk_input2_div").css("display","none");
								$("#risk_input2").prop('disabled', true);
							}
					}
				  else
					{
							$("#avg_div, #cmnt_div").css("display","block");
							$("#avg, #cmnt").prop('disabled', false);
							$("#risk_input1_div, #risk_input2_div").css("display","none");
							$("#risk_input1, #risk_input2").prop('disabled', true);
							$("#avg").prop('disabled',true);
							$("#avg").val('');
					}

					if (val1 == 0) {
						$("#ques").val("");
						$("#quesid").val("");
						$("#quesser").val("");
						$("#submit_btn").prop('disabled',true);
						$("#next_btn").prop('disabled',true);
					} else {
						$("#ques").val(data[count].ques_serial+". "+data[count].ques);
						$("#quesid").val(data[count].id);
						$("#quesser").val(data[count++].ques_serial);
						$("#answ").html("<option value=\"Yes\">Yes</option><option value=\"No\">No</option><option value=\"N/A\">N/A</option>");
						$("#answ").val("Yes");
						$("#risk_input1, #risk_input2").val("");
						$("#submit_btn").prop('disabled',false);
						$("#next_btn").prop('disabled',false);
					}
      });
			$("#answ").change(function(){
				if ($("#sid").val() == 4 && ($("#sidsub").val() == 10 || $("#sidsub").val() == 11 || $("#sidsub").val() == 12 || $("#sidsub").val() == 13 )) {
					if ($(this).val() != "Yes") {
						$("#avg").prop('disabled', true).val();
					}
					else if(($(this).val() == "Yes")) {
						$("#avg").prop('disabled', false).val();
					}
				}
			});

			$("#next_btn").on('click', function() {
				fetch_answered_ques($("#sid").val(),$("#sidsub").val());
				if(count < data.length){
					$("#ques").val(data[count].ques_serial+". "+data[count].ques);
					$("#quesid").val(data[count].id);
					if(data[count].id == 83){
						$("#answ").html("<option value=\"1\"><30%</option><option value=\"2\">30%-35%</option><option value=\"3\">35.1%-50%</option><option value=\"4\">>50%</option>");
						$("#answ").val("1");
					}else {
						$("#answ").val("Yes");
					}
					$("#quesser").val(data[count++].ques_serial);
					$("#comment").val("");
					$("#risk_input1, #risk_input2").val("");
				} else {
					alert("Please procced to next catogory or sub-catogory.");
					$("#ques").val("Please procced to next catogory or sub-catogory.");
					$("#quesid").val("");
					$("#quesser").val("");
					$("#answ").val("Yes");
					$("#comment").val("");
					$("#risk_input1, #risk_input2").val("");
					$("#submit_btn").prop('disabled',true);
					$("#next_btn").prop('disabled',true);
				}
			});
			$("#cgt_form").submit(function(e){
				e.preventDefault();
				submit_data(this,$("#cgt"));
			});
			$("#grt_form").submit(function(e){
				e.preventDefault();
				submit_data(this,$("#grt"));
			});
			$("#loan_form").submit(function(e){
				e.preventDefault();
				submit_data(this,$("#loan"));
			});
			$("#centre_meet_form").submit(function(e){
				e.preventDefault();
				submit_data(this,$("#center_meet"));
			});
			$("#answer_form").submit(function(e) {
					let form_data = $(this).serializeArray();
					e.preventDefault();
					$.ajax({
					method: 'POST',
					url: 'Controller/Parseaudit_format.php',
					data: form_data,
					success: function(resp) {
						let jsdata = JSON.parse(resp);
						//console.log(resp);
						fetch_answered_ques($("#sid").val(),$("#sidsub").val());
						if(jsdata.status == "Success")
						{
							alert(jsdata.msg);
							if(count < data.length){
								$("#ques").val(data[count].ques_serial+". "+data[count].ques);
								$("#quesid").val(data[count].id);
								if(data[count].id == 83){
									$("#answ").html("<option value=\"1\"><30%</option><option value=\"2\">30%-35%</option><option value=\"3\">35.1%-50%</option><option value=\"4\">>50%</option>");
								}else {
									$("#answ").val("Yes");
								}
								$("#quesser").val(data[count++].ques_serial);
								$("#avg").val("");
								$("#comment").val("");
								$("#risk_input1, #risk_input2").val("");
							} else {
								alert("Please procced to next catogory or sub-catogory.");
								$("#ques").val("All Done! Please procced to next catogory or sub-catogory.");
								$("#quesid").val("");
								$("#quesser").val("");
								$("#answ").val("Yes");
								$("#avg").val("");
								$("#comment").val("");
								$("#risk_input1, #risk_input2").val("");
								$("#submit_btn").prop('disabled',true);
								$("#next_btn").prop('disabled',true);
							}
						}
						else {
							alert(jsdata.msg);
							return false;
						}
					}
				});
			});
			$("#hr_annex_form").on('submit', function(e) {
				let form_data = $(this).serializeArray();
				e.preventDefault();
				$.ajax({
					method:'POST',
					url:'Controller/Parseaudit_format.php',
					data: form_data,
					success:function(resp){
						let json_data = JSON.parse(resp);
						if (json_data.status == "Success") {
							alert(json_data.msg);
							$("#hr_annex_form")[0].reset();
						}else if (json_data.status == "Fail") {
							alert(json_data.msg);
						}
					},
					error:function(err){
						console.log("Error : " +err);
					}
				});
			});
    });
  </script>
	<div class="modal fade" id="hr_anex" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="hr_annex_title" aria-hidden="true">
			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="hr_annex_title">HR Annexure</h4>
							<button type="button" title="Close" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-12">
									<form method="post" action="#" enctype="application/x-www-form-urlencoded" id="hr_annex_form">
										<div class="row">
												<div class="col-6">
														<div class="form-group">
																<label for="emp_code">Employee Code</label>
																<input type="text" class="form-control" placeholder="Employee Code" name="emp_code" id="emp_code" required="required"/>
														</div>
												</div>
										<!-- </div>
										<div class="row"> -->
												<div class="col-6">
														<div class="form-group">
																<label for="emp_name">Employee Name</label>
																<input type="text" class="form-control" placeholder="Employee Name" name="emp_name" id="emp_name" required="required"/>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-12">
														<div class="form-group">
																<label for="reg_desig">Designation as per Attendance Register</label>
																<select class="form-control" id="reg_desig" name="reg_desig" required="required">
																	<option value="0">Select Designation</option>
																	<option value="BM">BM</option>
																	<option value="ABM">ABM</option>
																	<option value="TCRO">TCRO</option>
																	<option value="Sr.CRO">Sr.CRO</option>
																	<option value="CRO">CRO</option>
																	<option value="Sr.BM">Sr.BM</option>
																	<option value="Any">Any</option>
																</select>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-12">
														<div class="form-group">
															<label for="soft_desig">Designation as per Software</label>
															<select class="form-control" id="soft_desig" name="soft_desig" required="required">
																<option value="0">Select Designation</option>
																<option value="BM">BM</option>
																<option value="ABM">ABM</option>
																<option value="TCRO">TCRO</option>
																<option value="Sr.CRO">Sr.CRO</option>
																<option value="CRO">CRO</option>
																<option value="Sr.BM">Sr.BM</option>
																<option value="Any">Any</option>
															</select>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-6">
														<div class="form-group">
																<label for="doj">Joining Date at Branch</label>
																<input type="date" class="form-control" placeholder="Joining Date at Branch" name="doj" id="doj" required="required"/>
														</div>
												</div>
										<!-- </div>
										<div class="row"> -->
												<div class="col-6">
														<div class="form-group">
																<label for="tot_month">Total Months at Branch</label>
																<input type="number" class="form-control" placeholder="Total Months at Branch" name="tot_month" id="tot_month" required="required"/>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-6">
														<div class="form-group">
																<label for="id_card">ID Card Available</label>
																<select class="form-control" name="id_card" id="id_card" required="required">
																	<option value="Yes">Yes</option>
																	<option value="No">No</option>
																</select>
														</div>
												</div>
										<!-- </div>
										<div class="row"> -->
											<div class="col-6">
												<div class="form-group">
													<label for="emp_type">Employment Type</label>
													<select id="emp_type" name="emp_type" class="form-control" required="required">
														<option value="Permanent">Permanent</option>
														<option value="Temporary">Temporary</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-12">
												<div class="form-group">
													<label for="emp_gra">Comments/Employee Grievances</label>
													<textarea class="form-control" rows="1" id="emp_gra" name="emp_gra"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-12">
												<input type="submit" class="btn btn-sm btn-outline-success" name="hr_annex_submit" id="hr_annex_submit" value="Submit"/>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
			</div>
	</div>
	<!-- The Modal -->
	<div class="modal fade" id="cgt" role="dialog" data-backdrop="static" data-keyboard='false'>
	  <div class="modal-dialog">
	    <div class="modal-content">

	      <!-- Modal Header -->
	      <div class="modal-header">
	        <h4 class="modal-title">CGT</h4>
	        <!-- <button type="button" class="close" data-dismiss="modal" title="Close Modal">&times;</button> -->
	      </div>

	      <!-- Modal body -->
	      <div class="modal-body">
					<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<form method="post" action="#" enctype="application/x-www-form-urlencoded" id="cgt_form">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label for="cgt_date">Date of CGT Visited</label>
												<input type="date" name="cgt_date" id="cgt_date" class="form-control form-control-sm" placeholder="Date of CGT Visited" required="required"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label for="vill_name">Village Name</label>
												<input type="text" name="vill_name" id="vill_name" class="form-control form-control-sm" placeholder="Village Name" required="required"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label for="tot_member">Total Members present in CGT</label>
												<input type="text" name="tot_member" id="tot_member" class="form-control form-control-sm" placeholder="Total Members present in CGT" required="required"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label for="tot_vis_mem">Total Members in visited/present CGT</label>
												<input type="text" name="tot_vis_mem" id="tot_vis_mem" class="form-control form-control-sm" placeholder="Total Members in visited/present CGT" required="required"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label for="cro_name">CRO's Name</label>
												<input type="text" name="cro_name" id="cro_name" class="form-control form-control-sm" placeholder="CRO's Name" required="required"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<input type="submit" name="cgt_submit" class="btn btn-outline-success btn-sm" value="Submit"/>
											</div>
										</div>
									</div>
								</form>
							</div>
					</div>
	      </div>

	      <!-- Modal footer -->
	      <div class="modal-footer">
	        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
	      </div>

	    </div>
	  </div>
	</div>
	<!-- The Modal -->
<div class="modal fade" id="grt" role="dialog" data-backdrop="static" data-keyboard='false' tabindex="-1" aria-labelledby="grt_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="grt_title">GRT</h4>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
      </div>

      <!-- Modal body -->
      <div class="modal-body">
				<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<form method="post" action="#" enctype="application/x-www-form-urlencoded" id="grt_form">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="grt_date">Date of GRT Visited</label>
											<input type="date" name="grt_date" id="grt_date" class="form-control form-control-sm" placeholder="Date of GRT Visited" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="vill_name">Village Name</label>
											<input type="text" name="vill_name" id="vill_name" class="form-control form-control-sm" placeholder="Village Name" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cro_name">CRO's Name</label>
											<input type="text" name="cro_name" id="cro_name" class="form-control form-control-sm" placeholder="CRO's Name" required="required"/>
										</div>
									</div>
								</div>
								 <div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="grt_name">GRT</label>
											<input type="text" name="grt_name" id="grt_name" class="form-control form-control-sm" placeholder="GRT" required="required"/>
										</div>
									</div>
								</div>
								<!--<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for=""></label>
											<input type="date" name="" class="form-control form-control-sm" placeholder="" required="required"/>
										</div>
									</div>
								</div> -->
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<input type="submit" name="grt_submit" class="btn btn-outline-success btn-sm" value="Submit"/>
										</div>
									</div>
								</div>
							</form>
						</div>
				</div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
      </div>

    </div>
  </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="loan" role="dialog" data-backdrop="static" data-keyboard='false'>
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Loan Disbursement</h4>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
      </div>

      <!-- Modal body -->
      <div class="modal-body">
				<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<form method="post" action="#" enctype="application/x-www-form-urlencoded" id="loan_form">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="loan_date">Date of loan Disbursement</label>
											<input type="date" name="loan_date" id="loan_date" class="form-control form-control-sm" placeholder="Date of loan Disbursement" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cen_name">Centre Number</label>
											<input type="text" name="cen_name" id="cen_name" class="form-control form-control-sm" placeholder="Centre Number" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="exe_client">Existing clients (subsequent cycle loan)</label>
											<input type="text" name="exe_client" id="exe_client" class="form-control form-control-sm" placeholder="Existing clients" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="new_client">New Clients</label>
											<input type="text" name="new_client" id="new_client" class="form-control form-control-sm" placeholder="New Clients" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cro_name">CRO's Name</label>
											<input type="text" name="cro_name" id="cro_name" class="form-control form-control-sm" placeholder="CRO's Name" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<input type="submit" name="loan_submit" class="btn btn-outline-success btn-sm" value="Submit"/>
										</div>
									</div>
								</div>
							</form>
						</div>
				</div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
      </div>

    </div>
  </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="center_meet" role="dialog" data-backdrop="static" data-keyboard='false'>
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Centre Meeting</h4>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
      </div>

      <!-- Modal body -->
      <div class="modal-body">
				<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<form method="post" action="#" enctype="application/x-www-form-urlencoded" id="centre_meet_form">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cen_date">Date of Centre Meeting Visit</label>
											<input type="date" name="cen_date" id="cen_date" class="form-control form-control-sm" placeholder="Date of Centre Meeting Visit" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cen_num">Centre Number</label>
											<input type="text" name="cen_num" id="cen_num" class="form-control form-control-sm" placeholder="Centre Number" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="">Is New disbursed centre visited during Audit (Previous quarter)</label>
											<select name="cen_sel" id="cen_sel" class="form-control form-control-sm" required="required">
												<option value="yes">Yes</option>
												<option value="no">No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="tot_cen">Total Clients Present in centres</label>
											<input type="text" name="tot_cen" id="tot_cen" class="form-control form-control-sm" placeholder="Total Clients Present in centres" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="tot_vis_cen">Total Clients in Visited centres</label>
											<input type="text" name="tot_vis_cen" id="tot_vis_cen" class="form-control form-control-sm" placeholder="Total Clients in Visited centres" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<label for="cro_name">CRO's Name</label>
											<input type="text" name="cro_name" id="cro_name" class="form-control form-control-sm" placeholder="CRO's Name" required="required"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="form-group">
											<input type="submit" name="cen_submit" class="btn btn-outline-success btn-sm" value="Submit"/>
										</div>
									</div>
								</div>
							</form>
						</div>
				</div>
      </div>

      <!-- Modal footer -->
     <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
      </div>

    </div>
  </div>
</div>
<div class="container myansmodal">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="jumbotron">
                <h2 align="center" style="color:#d51c22;">Start Auditing</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
					<form method="post" action="#" enctype="application/x-www-form-urlencoded">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-6">
          			<button type="button" class="btn btn-primary btn-sm" disabled="disabled"><?=$_SESSION['brnchcode']?></button>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-6">
          			<input type="submit" class="btn btn-outline-primary btn-sm" name="change_branch" id="change_pass" value="Change Branch" />
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hr_anex" id="hr_anex_button">
								  Add Annexure
								</button>
							</div>
						</div>
					</form>
        </div>
    </div>
		<div class="row">
				<div class="col-12">
						<label style="color:#28a745;">Answered Questions:-&nbsp;</label><label id="answered_ques" style="color:#28a745;"></label>
				</div>
		</div>
    <div class="row" id="ques_div">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form method="post" action="#" id="answer_form" enctype="application/x-www-form-urlencoded">
              <div class="row">
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="form-group">
        						<label for="sid">Question Catogory</label>
        						<select name="sid" id="sid" class="form-control form-control-sm" required="required">
        							<option value="0">Select Catogory</option>
        						</select>
        					</div>
        				</div>
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="form-group">
        						<label for="sidsub">Select Sub-Catogory</label>
        						<select name="sidsub" id="sidsub" class="form-control form-control-sm" required="required">
        							<option value="0">Select Sub-Catogory</option>
        						</select>
        					</div>
        				</div>
        		 </div>
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label for="ques">Question</label>
                    <textarea class="form-control form-control-sm" name="ques" id="ques" rows="3" readonly="readonly" style="color:#d51c22;">Question</textarea>
										<input type="hidden" name="quesid" id="quesid" />
										<input type="hidden" name="quesser" id="quesser" />
                  </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                      <label for="answ">Select Answer</label>
                      <select name="answ" class="form-control form-control-sm" id="answ">
                          <!-- <option value="Yes">Yes</option>
                          <option value="No">No</option>
                          <option value="N/A">N/A</option> -->
                      </select>
                    </div>
                  </div>
									<div class="col-lg-4 col-md-4 col-sm-12">
										<div class="form-group" id="avg_div">
												<label for="avg">Average</label>
												<input type="number" class="form-control form-control-sm" name="avg" id="avg" placeholder="Enter Average" required="required" disabled="disabled"/>
										</div>
										<div class="form-group" id="risk_input1_div" style="display:none;">
												<label for="risk_input1" id="risk_input1_label"></label>
												<input type="number" class="form-control form-control-sm" name="risk_input1" id="risk_input1" required="required"/>
										</div>
									</div>
                  <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="form-group" id="cmnt_div">
                      <label for="ques">Comment</label>
                      <textarea class="form-control form-control-sm" name="comment" id="comment" rows="2"></textarea>
                    </div>
										<div class="form-group" id="risk_input2_div" style="display:none;">
											<label for="risk_input2" id="risk_input2_label"></label>
											<input type="number" class="form-control form-control-sm" name="risk_input2" id="risk_input2" required="required"/>
                    </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6">
									<button class="btn btn-outline-primary btn-sm float-left" type="button" id="next_btn">Next Question</button>
                </div>
                <div class="col-lg-6 col-md-6">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <input type="submit" class="btn btn-outline-success btn-sm float-lg-right" value="Submit" name="submit_btn" id="submit_btn"/>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>

<?php
 }
 ?>
 <?php
 	include_once 'Includes/footer.php';
 ?>
