<?php
	include("../Config/Mysql/conn.php");
	class AppLogin extends Connection
	{
		private $user = NULL;
		private $pass = NULL;
		private $con = NULL;
		public function __construct()
		{
			parent:: __construct();
		}
		public function setuserdtls($uname,$psw)
		{
			$this->user = $uname;
			$this->pass = md5($psw);
		}
		public function login()
		{
			if(substr($this->user,0,3) == 'VIS') {
				$qry = "SELECT * FROM `visitor_dtls` WHERE `username`='".$this->user."' AND `login_pass` = '".$this->pass."' AND `flag` = '0'";
				$result = $this->getquerystr($qry);
				$res = mysqli_fetch_row($result);
				if(mysqli_num_rows($result) > 0 && !strcmp($res[9],$this->user)) {
					$this->getquerystr("INSERT INTO `login_dtls`(`id`, `visitor_id`, `dt`, `flag`) VALUES (NULL,'".$res[9]."',current_timestamp(),'0')");
					echo $data = '[{"id":"'.$res[9].'", "usertype" : "Visitor"}]';
				} else {
					$msg = 'Invalid Credentials';
				}
			} else {
				$qry = "SELECT * FROM `users` WHERE `user` = '".$this->user."' AND `pass` = '".$this->pass."' AND `flag` = '0'";
				$result = $this->getquerystr($qry);
				$res = mysqli_fetch_row($result);
				if(mysqli_num_rows($result) > 0 && !strcmp($res[1],$this->user)) {
					$this->getquerystr("INSERT INTO `login_dtls`(`id`, `visitor_id`, `dt`, `flag`) VALUES (NULL,'".$res[1]."',current_timestamp(),'0')");
					echo $data = '[{"id":"'.$res[1].'" , "usertype" : "Admin"}]';
				} else {
					$msg = 'Invalid Credentials';
				}
			}
		}
		public function branchdtls($vid)
		{
			$data = '';
			$qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$vid."'";
			$result = $this->getquerystr($qry);
			$res = mysqli_fetch_row($result);
			$getbrn = "SELECT `brn_code`, `brn_name`,`reg_name`,`brn_mng` FROM `branch_details` WHERE `mapto` = '".$res[0]."'";
			$result = $this->getquerystr($getbrn);
			while($getres = mysqli_fetch_row($result))
			{
				if($data != '')
					$data .= ',';
				$data .= '{"code" : "'.$getres[0].'", "name" : "'.$getres[1].'", "reg" : "'.$getres[2].'", "bm" : "'.$getres[3].'" }';
			}
			return $data = '['.$data.']';
		}
		public function branchdtl()
		{
			$data = '';
			$getbrn = "SELECT `brn_code`, `brn_name`,`reg_name`,`brn_mng` FROM `branch_details`";
			$result = $this->getquerystr($getbrn);
			while($getres = mysqli_fetch_row($result))
			{
				if($data != '')
					$data .= ',';
				$data .= '{"code" : "'.$getres[0].'", "name" : "'.$getres[1].'", "reg" : "'.$getres[2].'", "bm" : "'.$getres[3].'" }';
			}
			return $data = '['.$data.']';
		}
		public function getques($cid,$sid)
		{
			$data = '';
			$qry = "SELECT `id`, `ques` FROM `question` WHERE `catog` = '".$cid."' AND `sub_catog` = '".$sid."' ORDER BY `ques_serial` asc";
			$result = $this->getquerystr($qry);
			while($res = mysqli_fetch_row($result))
			{
				if($data != '')
					$data .= ',';
				$data .= '{"id" : "'.$res[0].'", "ques" : "'.$res[1].'"}';
			}
			return $data ='['.$data.']';
		}
		public function setans($brnid,$cid,$scid,$qid,$ans,$cmnt = '',$user)
		{
			$qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$user."'";
			$user = $this->getquerystr($qry);
			$user = mysqli_fetch_row($user);
			$qry = "SELECT `id` FROM `branch_details` WHERE `brn_code` = '".$brnid."'";
			$result = $this->getquerystr($qry);
			if(mysqli_num_rows($qry) > 0)
			{
				$brnid = mysqli_fetch_row($result);
				if ($_POST['sid'] == "7") {
          $input1 = isset($_POST['risk_input1']) ? $_POST['risk_input1'] : '';
          $input2 = isset($_POST['risk_input2']) ? $_POST['risk_input2'] : '';
            $qry = "INSERT INTO `answer` (`id`, `brnch`, `cid`, `scid`, `quid`, `answ`, `avg`, `anex_id`, `cmnt`, `dt`, `user`, `flag`)
							VALUES (NULL, '".$brnid."', '".$cid."', '".$scid."', '".$qid."', '".$ans."', '".$input1."', '".$input2."', '', current_timestamp(), '".$user[0]."', '1')";
            $result = $obj->getquerystr($qry);
        }else {
					$anex_Id = array();
          if($_POST['sid'] == "4" && ($_POST['sidsub'] == "10" || $_POST['sidsub'] == "11" || $_POST['sidsub'] == "12" || $_POST['sidsub'] == "13")) {
            $qry = "SELECT MAX(`id`) FROM `branch_operation_anxe` WHERE `scid` = '".$_POST['sidsub']."' AND `branch_id` = '".$brnid."'";
            $result_anex = $obj->getquerystr($qry);
            $anex_Id = mysqli_fetch_row($result_anex);
          }
          $anex_Id = isset($anex_Id[0]) ? $anex_Id[0] : 0;
          $avg = isset($_POST['avg']) ? $_POST['avg'] : 0;
					$qry = "INSERT INTO `answer` (`id`, `brnch`, `cid`, `scid`, `quid`, `answ`, `avg`, `anex_id`, `cmnt`, `dt`, `user`, `flag`)
					VALUES (NULL, '".$brnid."', '".$cid."', '".$scid."', '".$qid."', '".$ans."', '".$avg."', '".$anex_Id."', '".$cmnt."', current_timestamp(), '".$user[0]."', '1')";
					$result = $this->getquerystr($qry);
				}
				if($result) {
					$msg = array('msg'=> "Answered successfully please procced to next question.", 'status' => "Success");
				}else {
					$msg = array('msg'=> "Something Went Wrong", 'status' => "Fail");
				}
		  }
		}

		public function changepass($cnfpass,$newpass,$vid)
		{
			$qry = "SELECT * FROM `visitor_dtls` WHERE `login_pass` = '".md5($cnfpass)."' AND `username` = '".$vid."'";
			if(mysqli_num_rows($this->getquerystr($qry)) == 1)
			{
					$result = $this->getquerystr("UPDATE `visitor_dtls` SET `password`='".$newpass."',`login_pass`='".md5($newpass)."' WHERE `username` = '".$vid."'");
					if($result) {
		 			 $msg = array('msg'=> "Password changed successfully.",'status' => "Success");
		 		 } else {
		 			 $msg = array('msg'=> "Something Went Wrong.",'status' => "Fail");
		 		 }
			} else {
				$msg = array('msg'=> "Wrong credentials.",'status' => "Fail");
			}
			return json_encode($msg);
		}
		public function getvisitor()
		{
			$sql = "SELECT `name`, `username`, `dist`, `state`, `phone`  FROM `visitor_dtls` WHERE `flag` = '0'";
			$result = $this->getquerystr($sql);
			while($res = mysqli_fetch_row($result))
			{
				if($data != '')
					$data .= ',';
				$data .= '{"name":"'.$res[0].'", "code":"'.$res[1].'", "desi":"'.$res[2].'", "loc":"'.$res[3].'", "phone":"'.$res[4].'"}';
			}
			return $data = '['.$data.']';
		}
		// public function getcato() {
		// 	$qry = "SELECT `id`,`catog` FROM `catogory` order by `id` asc";
		// 	$result = $this->getquerystr($qry);
		// 	$cato = mysqli_fetch_all($result, MYSQLI_ASSOC);
		// 	$qry = "SELECT `id`, `cato`, `subcatog` FROM `subcatogory`  order by `id` asc";
		// 	$result = $this->getquerystr($qry);
		// 	$subcato = mysqli_fetch_all($result, MYSQLI_ASSOC);
		// 	$data = array('cato' => $cato, 'subcato' => $subcato);
		// 	return json_encode($data);
		// }
		public function getcato() {
			$data = array();
			$qry = "SELECT `id` As `catid`,`catog` FROM `catogory` order by `id` asc";
			$result = $this->getquerystr($qry);
			while($cato = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
					$sql = "SELECT `id`, `cato`, `subcatog` FROM `subcatogory` WHERE `cato` = '".$cato['catid']."' order by `id` asc";
					$subresult = $this->getquerystr($sql);
					$subcato = mysqli_fetch_all($subresult, MYSQLI_ASSOC);
					$subcato  = array('subcato' => $subcato);
					$cato = array_merge($cato,$subcato);
					array_push($data,$cato);
			}
			return json_encode($data);
		}
	  public function crt($var,$var1,$var2,$var3,$var4,$var5,$var6,$var7) {
		 $qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$var7."'";
		 $user = $this->getquerystr($qry);
		 $user = mysqli_fetch_row($user);
		 $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `vill_name`, `tot`, `tot1`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '".$var."', '".$var1."', '".$var2."', '".$var3."', '".$var4."', '".$var5."', '".$var6."', current_timestamp(), '".$user[0]."', '1')";
		 $result = $this->getquerystr($qry);
		 if($result) {
			 $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
		 } else {
			 $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
		 }
		 return json_encode($msg);
		}
	  public function grt($var,$var1,$var2,$var3,$var4,$var5,$var6) {
		 $qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$var5."'";
		 $user = $this->getquerystr($qry);
		 $user = mysqli_fetch_row($user);
		 $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `vill_name`, `cro_name`, `grt_name`, `dt`, `user`, `flag`) VALUES (NULL, '".$var."', '".$var1."', '".$var2."', '".$var3."', '".$var4."', '".$var6."', current_timestamp(), '".$user[0]."', '1')";
		 $result = $this->getquerystr($qry);
		 if($result) {
			 $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
		 } else {
			 $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
		 }
		 return json_encode($msg);
		}
	  public function loan($var,$var1,$var2,$var3,$var4,$var5,$var6,$var7) {
		 $qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$var7."'";
		 $user = $this->getquerystr($qry);
		 $user = mysqli_fetch_row($user);
		 $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `centre_no`, `exe_clients`, `new_clients`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '".$var."', '".$var1."', '".$var2."', '".$var3."', '".$var4."', '".$var5."', '".$var6."', current_timestamp(), '".$user[0]."', '1')";
		 $result = $this->getquerystr($qry);
		 if($result) {
			 $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
		 } else {
			 $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
		 }
		 return json_encode($msg);
		}
	  public function centre_meet($var,$var1,$var2,$var3,$var4,$var5,$var6,$var7,$var8) {
		 $qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$var8."'";
		 $user = $this->getquerystr($qry);
		 $user = mysqli_fetch_row($user);
		 $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `tot`, `tot1`, `centre_no`, `new_dis_cen`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '".$var."', '".$var1."', '".$var2."', '".$var3."', '".$var4."', '".$var5."', '".$var6."', '".$var7."', current_timestamp(), '".$user[0]."', '1')";
		 $result = $this->getquerystr($qry);
		 if($result) {
			 $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
		 } else {
			 $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
		 }
		 return json_encode($msg);
		}
	}
		$obj = new AppLogin();
		$pg = $_REQUEST['pg'];
		$vid = isset($_REQUEST['vid']) ? $_REQUEST['vid'] : '';
		$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
		$user = isset($_POST['user']) ? $_POST['user'] : '';
		$pass = isset($_POST['pass']) ? $_POST['pass'] : '';

		switch($pg)
		{
			case 'login':
				$obj->setuserdtls($user, $pass);
				$obj->login();
				break;
			case 'brnchdtl':
				echo $obj->branchdtl();
				break;
			case 'ques':
				echo $obj->getques($vid, $sid);
				break;
			case 'answer':
				$obj->setans($_POST['brnid'], $_POST['cid'], $_POST['scid'], $_POST['qid'], $_POST['ans'], $_POST['cmnt'], $_POST['login_visitor']);
				break;
			case 'changepass':
				echo $obj->changepass($_POST['cnfpass'], $_POST['newpass'], $_POST['vid']);
				break;
			case 'getvisitor':
				echo $obj->getvisitor();
				break;
			case 'getbranch':
				echo $obj->branchdtl();
				break;
			case 'getcato':
				echo $obj->getcato();
				break;
			case 'crt':
				echo $obj->crt($_POST['scid'], $_POST['brnchid'], $_POST['cgt_date'], $_POST['vill_name'], $_POST['tot_member'], $_POST['tot_membertot_member'], $_POST['cro_name'], $_POST['loginuserid']);
				break;
			case 'grt':
				echo $obj->grt($_POST['scid'], $_POST['brnchid'], $_POST['grt_date'], $_POST['vill_name'], $_POST['cro_name'], $_POST['loginuserid'], $_POST['grt_name']);
				break;
			case 'loan':
				echo $obj->loan($_POST['scid'], $_POST['brnchid'], $_POST['loan_date'], $_POST['cen_name'], $_POST['exe_client'], $_POST['new_client'], $_POST['cro_name'], $_POST['loginuserid']);
				break;
			case 'centre_meet':
				echo $obj->centre_meet($_POST['scid'], $_POST['brnchid'], $_POST['cen_date'], $_POST['tot_cen'], $_POST['tot_vis_cen'], $_POST['cen_num'], $_POST['cen_sel'], $_POST['cro_name'], $_POST['loginuserid']);
				break;
		}
?>
