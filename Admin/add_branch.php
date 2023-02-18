<?php
	$page_title = "Add Branch";
	include_once 'Includes/header.php';
	include_once 'Controller/Parseaddbranch.php';
?>

<div class="container">
	<h2 align="center"><u>Add Branch</u></h2>
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
	<form action="#" method="post" enctype="application/x-www-form-urlencoded">
		<fieldset class="border p-2">
			<legend class="w-auto">AUDIT REPORT DETAILS</legend>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bcode">Branch Code</label>
						<input type="text" name="bcode" id="bcode" class="form-control form-control-sm" Placeholder='Branch Code'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bname">Branch Name</label>
						<input type="text" name="bname" id="bname" class="form-control form-control-sm" Placeholder='Branch Name'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bfd">Branch Formation Date</label>
						<input type="date" name="bfd" id="bfd" class="form-control form-control-sm" Placeholder='Branch Formation Date'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="region">Region Name</label>
						<input type="text" name="region" id="region" class="form-control form-control-sm" Placeholder='Region Name'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bm">Branch Manager</label>
						<input type="text" name="bm" id="bm" class="form-control form-control-sm" Placeholder='Branch Manager'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="am">Area Manager</label>
						<input type="text" name="am" id="am" class="form-control form-control-sm" Placeholder='Area Manager'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="rm">Regional Manager</label>
						<input type="text" name="rm" id="rm" class="form-control form-control-sm" Placeholder='Regional Manager'/>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="adm">Auditor Name(s)</label>
						<input type="text" name="adn" id="adn" class="form-control form-control-sm" Placeholder='Auditor Name'/>
					</div>
				</div> -->
				<!--<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bsize">Branch Size</label>
						<input type="text" name="bsize" id="bsize" class="form-control form-control-sm" Placeholder='Branch Size'/>
					</div>
				</div>-->
			<!-- </div> -->
			<!--<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="asd">Audit Start Date</label>
						<input type="date" name="asd" id="asd" class="form-control form-control-sm" Placeholder='Audit Start Date'/>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="aed">Audit End Date</label>
						<input type="date" name="aed" id="aed" class="form-control form-control-sm" Placeholder='Audit End Date'/>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="dtcmp">Total Days to Complete</label>
						<input type="text" name="dtcmp" id="dtcmp" class="form-control form-control-sm" Placeholder='Total Days to Complete'/>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="pubd">Publication Date</label>
						<input type="date" name="pubd" id="pubd" class="form-control form-control-sm" Placeholder='Publication Date'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="aepf">Audit Evaluation Period From</label>
						<input type="date" name="aepf" id="aepf" class="form-control form-control-sm" Placeholder='Audit Evaluation Period From'/>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="form-group">
						<label for="aeptd">To date</label>
						<input type="date" name="aeptd" id="aeptd" class="form-control form-control-sm" Placeholder='To date'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tdip">Total Days in Period</label>
						<input type="date" name="tdip" id="tdip" class="form-control form-control-sm" Placeholder='Total Days in Period'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="rcode">Report Code (Branch/Month/Year)</label>
						<input type="text" name="rcode" id="rcode" class="form-control form-control-sm" Placeholder='Report Code(Branch/Month/Year)'/>
					</div>
				</div>
			</div>
		</fieldset>


		<fieldset class="border p-2">
		<legend class="w-auto">BRANCH OVERVIEW</legend>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="sedate">STATISTICS (End of the Audit Period) Date</label>
						<input type="date" name="sedate" id="sedate" class="form-control form-control-sm" Placeholder='STATISTICS (End of the Audit Period)'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="loacc">Loan Outstanding Accounts</label>
						<input type="text" name="loacc" id="loacc" class="form-control form-control-sm" Placeholder='Loan Outstanding Accounts'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tfo">Total Portfolio Outstanding (Rs)</label>
						<input type="text" name="tfo" id="tfo" class="form-control form-control-sm" Placeholder='Total Portfolio Outstanding (Rs)'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tcen">Total Centres in Branch</label>
						<input type="text" name="tcen" id="tcen" class="form-control form-control-sm" Placeholder='Total Centres in Branch'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="apc">Accounts per Centre</label>
						<input type="text" name="apc" id="apc" class="form-control form-control-sm" Placeholder='Accounts per Centre'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tpc">Total PAR Clients</label>
						<input type="text" name="tpc" id="tpc" class="form-control form-control-sm" Placeholder='Total PAR Clients'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="pout">PAR Outstanding (Rs)</label>
						<input type="text" name="pout" id="pout" class="form-control form-control-sm" Placeholder='PAR Outstanding (Rs)'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="parisk">Portfolio at Risk (%)</label>
						<input type="text" name="parisk" id="parisk" class="form-control form-control-sm" Placeholder='Portfolio at Risk (%)'/>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="border p-2">
			<legend class="w-auto">CENTER VISIT DETAILS</legend>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tcv">Total Centres Visited</label>
						<input type="text" name="tcv" id="tcv" class="form-control form-control-sm" Placeholder='Total Centres Visited'/>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="tccv">Total Clients in Visited Centre</label>
						<input type="text" name="tccv" id="tccv" class="form-control form-control-sm" Placeholder='Total Clients in Visited Centre'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="noscd">Number of total centre disbursed in last quarter</label>
						<input type="text" name="noscd" id="noscd" class="form-control form-control-sm" Placeholder='Number of total centre disbursed in last quarter'/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="nosnd">Number of New disbursed centre visited during Audit</label>
						<input type="text" name="nosnd" id="nosnd" class="form-control form-control-sm" Placeholder='Number of New disbursed centre visited during Audit'/>
					</div>
				</div>
			</div>-->

		<div class="row">
			<div class="col-lg-8 col-md-6">
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12">
				<input type="submit" name="submit" value="Submit" class="btn btn-outline-success btn-block"/>
			</div>
		</div>
	  </fieldset>
	</form>
</div>

<?php
	include_once 'Includes/footer.php';
 ?>
