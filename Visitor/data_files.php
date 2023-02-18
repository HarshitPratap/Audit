<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
		include("../Config/Mysql/conn.php");
		$obj = new Connection();
		$data =  array();
	//	$_POST['block'] = 'get_branch';
		switch ($_POST['block']) {
			case 'brn_dtls':
				$sql = "SELECT  `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng`, `updated` FROM `branch_details`";
				$result = $obj->getquerystr($sql);
				$data = array();
				while($res = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$id = array('id' => base64_encode("encodeuserid{$res['brn_code']}encodeuserid{$res['brn_name']}"));
					$res = array_merge($res, $id);
					array_push($data,$res);
				}
				echo json_encode($data);
				break;
			case 'cnfpass':
				$qry = "SELECT * FROM `visitor_dtls` WHERE `login_pass` = '".md5($_POST['pass'])."' AND `username` = '".$_POST['id']."'";
				$result = $obj->getquerystr($qry);
				if(mysqli_num_rows($result) == 0) {
					 $data = array('msg' => 'Invalid Current Password');
				}else {
					 $data = array('msg' => '');
				}
				echo json_encode($data);
				break;
			case 'update_brn_dtls':
				$id = $_POST['id'];
				$id = base64_decode($id);
				$id = explode("encodeuserid",$id);
				$qry = "SELECT  `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng` FROM `branch_details` Where `brn_code` ='".$id[1]."'";
				$result = $obj->getquerystr($qry);
				$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
				echo json_encode($data);
				break;
			case 'get_branch':
				$sql = "SELECT `id`, `brn_code`, `brn_name` FROM `branch_details`";
				$result = $obj->getquerystr($sql);

				$data = mysqli_fetch_all($result,MYSQLI_ASSOC);

				echo json_encode($data);
				break;
			case 'getcato':
				$qry = "SELECT `id`,`catog` FROM `catogory` order by `id` asc";
				$result = $obj->getquerystr($qry);
				$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
				echo json_encode($data);
				break;
			case 'getsubcato':
				$qry = "SELECT `id`, `subcatog` FROM `subcatogory` WHERE `cato` = '".$_POST['cato']."' order by `id` asc";
				$result = $obj->getquerystr($qry);
				$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
				echo json_encode($data);
				break;
			case 'getques':
				$qry = "SELECT  `id`, `ques`, `ques_serial` FROM `question` WHERE `catog` = '".$_POST['cato']."' AND `sub_catog` = '".$_POST['subcato']."' ORDER BY `ques_serial` asc";
				$result = $obj->getquerystr($qry);
				$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
				echo json_encode($data);
				break;
			case 'get_answered_ques':
				$qry = "SELECT `ques_serial` FROM `answer` WHERE `brnch` = '".$_POST['branch']."' AND `cid` = '".$_POST['cato']."' AND `quid`= '".$_POST['subcato']."'";
				$result = $obj->getquerystr($qry);
				$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
				$qry = "SELECT COUNT(`id`) AS tot_ques FROM `question` WHERE `catog` = '".$_POST['cato']."'  AND `sub_catog` = '".$_POST['subcato']."'";
				$result = $obj->getquerystr($qry);
				$res = mysqli_fetch_all($result,MYSQLI_ASSOC);
				$data = array_merge($data,$res);
				echo json_encode($data);
				break;
			default:
				break;
		}

 }
?>
