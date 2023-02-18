<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      include_once '../../Config/Session.php';
      include_once '../../Config/Mysql/conn.php';
      $msg = array();
      $obj = new Connection();
      if(isset($_POST['sid']))
      {
        $flag = 0;
        if ($_POST['sid'] == "7") {
          $input1 = isset($_POST['risk_input1'])?$_POST['risk_input1']:'';
          $input2 = isset($_POST['risk_input2'])?$_POST['risk_input2']:'';
          if(mysqli_num_rows($obj->getquerystr("SELECT `id` FROM `answer` WHERE `brnch` = '".$_SESSION['brnchcode']."' AND `cid` = '".$_POST['sid']."' AND `scid` = '".$_POST['sidsub']."' AND `quid` = '".$_POST['quesid']."'")) == 0){
            $qry = "INSERT INTO `answer` (`id`, `brnch`, `cid`, `scid`, `quid`, `ques_ser`, `answ`, `avg`, `anex_id`, `cmnt`, `dt`, `user`, `flag`) VALUES (NULL, '".$_SESSION['brnchcode']."', '".$_POST['sid']."', '".$_POST['sidsub']."', '".$_POST['quesid']."', '".$_POST['quesser']."', '".$_POST['answ']."', '".$input1."', '".$input2."', '', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
            $result = $obj->getquerystr($qry);
          }else{
            $flag = 1;
          }
        }else {
          $anex_Id = array();
          if($_POST['sid'] == "4" && ($_POST['sidsub'] == "10" || $_POST['sidsub'] == "11" || $_POST['sidsub'] == "12" || $_POST['sidsub'] == "13")){
            $qry = "SELECT MAX(`id`) FROM `branch_operation_anxe` WHERE `scid` = '".$_POST['sidsub']."' AND `branch_id` = '".$_SESSION['brnchcode']."'";
            $result_anex = $obj->getquerystr($qry);
            $anex_Id = mysqli_fetch_row($result_anex);
          }
          $anex_Id = isset($anex_Id[0]) ? $anex_Id[0] : 0;
          $avg = isset($_POST['avg']) ? $_POST['avg'] : 0;
          $cmnt = isset($_POST['comment'])?$_POST['comment']:'';
          $qry = "SELECT `id` FROM `answer` WHERE `brnch` = '".$_SESSION['brnchcode']."' AND `cid` = '".$_POST['sid']."' AND `scid` = '".$_POST['sidsub']."' AND `quid` = '".$_POST['quesid']."' AND `anex_id` = '".$anex_Id."'";
          if(mysqli_num_rows($obj->getquerystr($qry)) == 0) {
            $qry = "INSERT INTO `answer` (`id`, `brnch`, `cid`, `scid`, `quid`, `ques_ser`, `answ`, `avg`, `anex_id`, `cmnt`, `dt`, `user`, `flag`) VALUES (NULL, '".$_SESSION['brnchcode']."', '".$_POST['sid']."', '".$_POST['sidsub']."', '".$_POST['quesid']."', '".$_POST['quesser']."', '".$_POST['answ']."', '".$avg."', '".$anex_Id."', '".$cmnt."', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
            $result = $obj->getquerystr($qry);
          }else {
            $flag = 1;
          }
        }
        if ($flag == 0) {
          if($result)
          {
            $msg = array('msg'=> "Answered successfully please procced to next question.",'status' => "Success");
          }else {
            $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
          }
        }elseif ($flag == 1) {
          $msg = array('msg'=> "You have already answered this question.",'status' => "Fail");
        }
      } elseif (isset($_POST['cgt_date'])) {
        $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `vill_name`, `tot`, `tot1`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '10', '".$_SESSION['brnchcode']."', '".$_POST['cgt_date']."', '".$_POST['vill_name']."', '".$_POST['tot_member']."', '".$_POST['tot_vis_mem']."', '".$_POST['cro_name']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
        $result = $obj->getquerystr($qry);
        if($result) {
          $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
        }else {
          $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
        }
      }elseif (isset($_POST['grt_date'])) {
        $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `vill_name`, `cro_name`, `grt_name`, `dt`, `user`, `flag`) VALUES (NULL, '11', '".$_SESSION['brnchcode']."', '".$_POST['grt_date']."', '".$_POST['vill_name']."', '".$_POST['cro_name']."', '".$_POST['grt_name']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
        $result = $obj->getquerystr($qry);
        if($result) {
          $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
        }else {
          $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
        }
      }elseif (isset($_POST['loan_date'])) {
        $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `centre_no`, `exe_clients`, `new_clients`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '12', '".$_SESSION['brnchcode']."', '".$_POST['loan_date']."', '".$_POST['cen_name']."', '".$_POST['exe_client']."', '".$_POST['new_client']."', '".$_POST['cro_name']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
        $result = $obj->getquerystr($qry);
        if($result)
        {
          $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
        }else {
          $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
        }
      }elseif (isset($_POST['cen_date'])) {
        $qry = "INSERT INTO `branch_operation_anxe` (`id`, `scid`, `branch_id`, `dated`, `tot`, `tot1`, `centre_no`, `new_dis_cen`, `cro_name`, `dt`, `user`, `flag`) VALUES (NULL, '13', '".$_SESSION['brnchcode']."', '".$_POST['cen_date']."', '".$_POST['tot_cen']."', '".$_POST['tot_vis_cen']."', '".$_POST['cen_num']."', '".$_POST['cen_sel']."', '".$_POST['cro_name']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0')";
        $result = $obj->getquerystr($qry);
        if($result)
        {
          $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
        }else {
          $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
        }
      }elseif (isset($_POST['emp_type'])) {
        $qry = "INSERT INTO `hr_annex` (`id`, `brnch_id`, `emp_code`, `emp_name`, `reg_desig`, `soft_desig`, `date_of_join`, `tot_month`, `id_card`, `employment_type`, `emp_gra`, `dt`, `flag`, `user`) VALUES (NULL, '".$_SESSION['brnchcode']."', '".$_POST['emp_code']."', '".$_POST['emp_name']."', '".$_POST['reg_desig']."', '".$_POST['soft_desig']."', '".$_POST['doj']."', '".$_POST['tot_month']."', '".$_POST['id_card']."', '".$_POST['emp_type']."', '".$_POST['emp_gra']."', current_timestamp(), '0', '".$_SESSION['loginuserid']."');";
        $result = $obj->getquerystr($qry);
        if($result)
        {
          $msg = array('msg'=> "Added successfully please procced to next steps.",'status' => "Success");
        }else {
          $msg = array('msg'=> "Something Went Wrong",'status' => "Fail");
        }
      }
      echo json_encode($msg);
    }
 ?>
