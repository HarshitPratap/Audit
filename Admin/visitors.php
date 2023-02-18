<?php
ob_start();
session_start();
if(!isset($_SESSION['login']) && $_SESSION['login'] != 'yes')
	header("location:../index.php");
	include("../Mysql/conn.php");
	$obj = new Connection();
	$conn = $obj->getconn();
	$data = '';
	$qry = mysqli_query($conn,"SELECT `name`, `addr`, `dist`, `state`, `phone`, `email`, `aadhar`, `pan` FROM `visitor_dtls` WHERE `flag` = '0'");
	while($res = mysqli_fetch_row($qry))
	{
		if($data != '')
			$data .= ',';
		$data .= '{"name":"'.$res[0].'", "addr":"'.$res[1].'", "dist":"'.$res[2].'", "state":"'.$res[3].'", "phone":"'.$res[4].'", "email":"'.$res[5].'", "aadhar":"'.$res[6].'", "pan":"'.$res[7].'"}';
	}
	echo $data = '['.$data.']';
 ?>