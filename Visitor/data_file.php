<?php 
	include("../Mysql/conn.php");
	$obj = new Connection();
	$pg = $_REQUEST['pg'];
	$data = '';
	switch($pg)
	{
		case 'branch_dtls':
			$sql = "SELECT  `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng`, `auditors_name`, `brn_size` FROM `branch_details` Where `mapto` = '".$_REQUEST['id']."'";
			$result = $obj->getquerystr($sql);
			while($res = mysqli_fetch_row($result))
			{
				if($data != '')
					$data .= ',';
				$data .= '{"code":"'.$res[0].'", "name":"'.$res[1].'", "bfd":"'.$res[2].'", "reg":"'.$res[3].'", "bm":"'.$res[4].'", "am":"'.$res[5].'", "rm":"'.$res[6].'", "an":"'.$res[7].'", "id":"'.base64_encode("encodeuserid{$res[0]}").'"}';
				
			}
			echo $data = '['.$data.']';
			break;
		case 'chngpass':
			$qry = "SELECT * FROM `visitor_dtls` WHERE `username` = '".$_REQUEST['id']."' AND `login_pass` = '".md5($_REQUEST['pass'])."'";
			$result = $obj->getquerystr($qry);
			if(mysqli_num_rows($result) == 0)
			{
				echo $data = '[{"msg":"Invalid Current Password."}]';
			}
			break;
	}
?>