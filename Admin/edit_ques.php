<?php
$page_title = "Edit Questionaries | Update Questionaries";
include_once 'Includes/header.php';
include_once 'Controller/Parseeditques.php';
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
				'block': "editques",
        'quesid': "<?=$_GET['pg']?>"
			},
			success: function(resp) {
				var json_data = JSON.parse(resp);
				var  cato = "", subcatog = "";
          $.each(json_data.ques, function(index, ques) {
						$("#quesserial").val(ques.ques_serial);
						$("#ques").val(ques.ques);
						$("#compliance").val(ques.comp);
  					$("#weight").val(ques.weightage);
  					$("#score").val(ques.score);
            $("#qid").val(ques.id);
          });
          cato += "<option value=\"0\">Select Catogory</option>";
          $.each(json_data.cato, function(index, opt) {
          if(json_data.ques[0].catog == opt.id)
          {
            cato += "<option selected=\"selected\" value=\""+ opt.id +"\">"+ opt.catog +"</option>";
          }
          else {
            cato += "<option value=\""+ opt.id +"\">"+ opt.catog +"</option>";
          }
          });
          subcatog += "<option value=\"0\">Select Sub-Catogory</option>";
          $.each(json_data.subcato, function(index, subcat) {
						if(json_data.ques[0].sub_catog == subcat.id)
						{
            	subcatog += "<option selected=\"selected\" value=\""+ subcat.id +"\">"+ subcat.subcatog +"</option>";
						}
						else {
							subcatog += "<option value=\""+ subcat.id +"\">"+ subcat.subcatog +"</option>";
						}
          });
          $("#sid").html(cato);
          $("#sidsub").html(subcatog);
			},
			error: function(err) {
				console.log("error : "+err);
			}
		});
		$(".form-control").prop('readonly' , true);
		$("#compliance").prop('readonly' , false);
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
            console.log(cato.id);
  					outp += "<option value=\""+ cato.id +"\">"+ cato.subcatog +"</option>";
  				});
  				$("#sidsub").html(outp);
  			},
  			error: function(err) {
  				console.log("error : "+err);
  			}
  		});
  	});
		$("form").submit(function(e) {
			$(".form-control").prop('disabled' , false);
		});
 });
	function funchk()
	{
		if(document.getElementById('sid').value == '0')
		{
			alert("Please Select Catogory.");
			document.getElementById('sid').style.borderColor="#D51C22";
			document.getElementById('sid').focus();
			return false;
		}
		else
		{
			document.getElementById("sid").style.borderColor='#999999';
		}
		if(document.getElementById('sidsub').value == '0')
		{
			alert("Please Select Sub-Catogory.");
			document.getElementById('sidsub').style.borderColor="#D51C22";
			document.getElementById('sidsub').focus();
			return false;
		}
		else
		{
			document.getElementById("sidsub").style.borderColor='#999999';
		}
		var num = /^[0-9]$/;
		if(!num.test(document.getElementById('quesserial').value))
		{
			alert("Please Enter Valid Question Serial Number.");
			document.getElementById('quesserial').style.borderColor="#D51C22";
			document.getElementById('quesserial').focus();
			return false;
		}
		else
		{
			document.getElementById("quesserial").style.borderColor='#999999';
		}
		if(document.getElementById('ques').value == 'Question' && document.getElementById('ques').value != "")
		{
			alert("Please Enter Question.");
			document.getElementById('ques').style.borderColor="#D51C22";
			document.getElementById('ques').focus();
			return false;
		}
		else
		{
			document.getElementById("ques").style.borderColor='#999999';
		}
		var dec = /^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$/;
		if(!dec.test(document.getElementById('weight').value))
		{
			alert("Please Enter Valid Weight.");
			document.getElementById('weight').style.borderColor="#D51C22";
			document.getElementById('weight').focus();
			return false;
		}
		else
		{
			document.getElementById("weight").style.borderColor='#999999';
		}
		if(!dec.test(document.getElementById('score').value))
		{
			alert("Please Enter Valid Score.");
			document.getElementById('score').style.borderColor="#D51C22";
			document.getElementById('score').focus();
			return false;
		}
		else
		{
			document.getElementById("score").style.borderColor='#999999';
		}
	}
</script>
<div class="container">
	<?php
		if(isset($msg))
		{
	?>
		<div class="alert alert-<?=$msg[1];?>"  role="alert">
			<?=$msg[0];?>
		</div>
    <script>
    setTimeout(function(){
        window.location.href='question_rpt.php';
    }, 3000);
    </script>
	<?php
		}
	?>
	<form action="#" method="post" enctype="multipart/form-data">
		<fieldset class="border p-2">
			<legend class="w-auto">Add Question</legend>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="form-group">
						<label for="sid">Question Catogory</label>
						<select name="sid" id="sid" disabled="true" class="form-control form-control-sm">
							<option value="0">Select Catogory</option>
						</select>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="form-group">
						<label for="sidsub">Select Sub-Catogory</label>
						<select name="sidsub" id="sidsub" disabled="true" class="form-control form-control-sm">
							<option value="0">Select Sub-Catogory</option>
						</select>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="form-group">
						<label for="quesserial">Question Serial No.</label>
						<input type="text" name="quesserial" id="quesserial" placeholder="Serial No." required="required" class="form-control form-control-sm"/>
				</div>
			</div>
		</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group">
						<label for="ques">Question</label>
						<textarea class="form-control form-control-sm" name="ques" id="ques" rows="4">Question</textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group">
						<label for="ques">Compliance</label>
						<textarea class="form-control form-control-sm" name="compli" id="compliance" rows="4">Compliance</textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="weight">Weightage Percentage</label>
						<input type="text" class="form-control form-control-sm" name="weight" id="weight" placeholder="Weightage Percentage"/>
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="score">Score Achieved</label>
						<input type="text" class="form-control form-control-sm" name="score" id="score" placeholder="Score Achieved"/>
            <input type="hidden" id= "qid" name = "qid" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-8 col-md-8">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12">
						<input type="submit" id="btn" name="submit" value="Edit | Update" class="btn btn-outline-success btn-block" onclick="return funchk();"/>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<?php
	include_once 'Includes/footer.php';
 ?>
