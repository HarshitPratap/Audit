<?php
$page_title = "Create User";
include_once 'Includes/header.php';
include_once 'Controller/Parsecreateuser.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Create User</title>
<script>
function funchk()
{
	if(document.getElementById('fname').value == '')
	{
		alert("Please Enter First Name.");
		document.getElementById('fname').style.borderColor="#D51C22";
		document.getElementById('fname').focus();
		return false;
	}
	else
	{
		document.getElementById("fname").style.borderColor='#999999';
	}
	if(document.getElementById('lname').value == '')
	{
		alert("Please Enter Last Name.");
		document.getElementById('lname').style.borderColor="#D51C22";
		document.getElementById('lname').focus();
		return false;
	}
	else
	{
		document.getElementById("lname").style.borderColor='#999999';
	}
	if(document.getElementById('addr').value == '')
	{
		alert("Please Enter Address");
		document.getElementById('addr').style.borderColor="#D51C22";
		document.getElementById('addr').focus();
		return false;
	}
	else
	{
		document.getElementById("addr").style.borderColor='#999999';
	}
	if(document.getElementById('dist').value == '')
	{
		alert("Please Enter Dist");
		document.getElementById('dist').style.borderColor="#D51C22";
		document.getElementById('dist').focus();
		return false;
	}
	else
	{
		document.getElementById("dist").style.borderColor='#999999';
	}
	if(document.getElementById('state').value == '')
	{
		alert("Please Enter State");
		document.getElementById('state').style.borderColor="#D51C22";
		document.getElementById('state').focus();
		return false;
	}
	else
	{
		document.getElementById("state").style.borderColor='#999999';
	}
	/*var phone = /^[0-9]{10}$/;
	if(!phone.test(document.getElementById("cont").value))
	{
		alert("Please Enter Valid Contact Number");
		document.getElementById("cont").focus();
		document.getElementById("cont").style.borderColor='#D51C22';
		return false;
	}
	else
	{
		document.getElementById("cont").style.borderColor='#999999';
	}
	var emid = /^[\w.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
	if(!emid.test(document.getElementById("emid").value))
	{
		alert("Please Enter Valid Email-Id");
		document.getElementById("emid").focus();
		document.getElementById("emid").style.borderColor="#D51C22";
		return false;
	}
	else
	{
		document.getElementById("emid").style.borderColor="#999999";
	}
	var addhar = /^[0-9]{12}$/;
	if(!addhar.test(document.getElementById("anum").value))
	{
		alert("Please Enter Valid Addhar Number");
		document.getElementById("anum").focus();
		document.getElementById("anum").style.borderColor = "#D51C22";
		return false;
	}
	else
	{
		document.getElementById("anum").styleborderColor = "#999999";
	}
	var pan = /^([A-Z]){5}([0-9]){4}([A-Z]){1}?$/;
	if(!pan.test(document.getElementById("pan").value))
	{
		alert("Please Enter Valid Pan Number");
		document.getElementById("pan").focus();
		document.getElementById("pan").style.borderColor = "#D51C22";
		return false;
	}
	else
	{
		document.getElementById("pan").styleborderColor = "#999999";
	}*/
}

</script>
<div class="container">
	<h3 align="center">Generate Code</h3>
	<?php
		if(isset($msg))
		{
	?>
		<div class="alert alert-success"  role="alert">
			<?=$msg;?>
		</div>
	<?php
		}
	?>
	<form method="post" action="#" enctype="multipart/form-data">
		<div class="row">
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="fname">First Name</label>

					<input type="text" name="fname" id="fname" class="form-control form-control-sm" Placeholder='First Name'/>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="mname">Middle Name</label>
					<input type="text" name="mname" id="mname" class="form-control form-control-sm" Placeholder='Middle Name'/>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="lname">Last Name</label>

					<input type="text" name="lname" id="lname" class="form-control form-control-sm" Placeholder='Last Name'/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="addr">Employee Id</label>

					<input type="text" name="addr" id="addr" class="form-control form-control-sm" Placeholder='Address' />
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="dist">Designation</label>

					<input type="text" name="dist" id="dist" class="form-control form-control-sm" Placeholder='District'/>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="state">Base Location</label>

					<input type="text" name="state" id="state" class="form-control form-control-sm" Placeholder='State'/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="cont">Contact Number</label>

					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">+91</span>
						</div>
						<input type="text" name="cont" id="cont" class="form-control form-control-sm" aria-describedby="basic-addon1" Placeholder='9999****88'/>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="emid">Email-Id</label>

					<input type="text" name="emid" id="emid" class="form-control" Placeholder='abc@xyz.co'/>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="anum">Addhar Number</label>

					<input type="text" name="anum" id="anum" class="form-control" Placeholder='1234******56'/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<div class="form-group">
					<label for="pan">Pan Number</label>
					<input type="text" name="pan" id="pan" class="form-control " placeholder='AB****123*Z' onkeydown="this.value = this.value.toUpperCase();"/>
				</div>
			</div>
			<div class="col-lg-4 col-md-4">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12">
				<label for="btn"></label>
				<input type="submit" value="Save" id="btn" name="submit" class="btn btn-outline-success btn-block" onclick="return funchk();"/>
			</div>
		</div>
	</form>
</div>
<?php
	include_once 'Includes/footer.php';
 ?>
