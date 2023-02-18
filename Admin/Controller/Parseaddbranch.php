<?php
  if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
  {
    include_once '../Config/Mysql/conn.php';
    $msg = '';
    $obj = new Connection();
    //$qry = "INSERT INTO `branch_details` (`id`, `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng`, `auditors_name`, `brn_size`, `audit_start_date`, `audit_end_date`, `total_days_to_complete`, `publ_date`, `audit_eval_per_frm`, `to_date`, `tot_date_inperi`, `rpt_code`, `sta_date`, `loan_out_acc`, `tot_port_acc`, `tot_cen_inbrc`, `acc_per_cen`, `tot_par_client`, `par_outs`, `port_at_risk`, `tot_cen_visitd`, `tot_client_in visited_cen`, `no_of_tot_cen_dis_in_las_qua`, `no_of_new_dis_cen_vis_dur_audit`, `dt`, `user`, `mapto`, `flag`) VALUES (NULL, '".$_POST['bcode']."', '".$_POST['bname']."', '".$_POST['bfd']."', '".$_POST['region']."', '".$_POST['bm']."', '".$_POST['am']."', '".$_POST['rm']."', '".$_POST['adn']."', '".$_POST['bsize']."', '".$_POST['asd']."', '".$_POST['aed']."', '".$_POST['dtcmp']."', '".$_POST['pubd']."', '".$_POST['aepf']."', '".$_POST['aeptd']."', '".$_POST['tdip']."', '".$_POST['rcode']."', '".$_POST['sedate']."', '".$_POST['loacc']."', '".$_POST['tfd']."', '".$_POST['tcen']."', '".$_POST['apc']."', '".$_POST['tpc']."', '".$_POST['pout']."', '".$_POST['parisk']."', '".$_POST['tcv']."', '".$_POST['tccv']."', '".$_POST['noscd']."', '".$_POST['nosnd']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0', '0')";
    $qry = "INSERT INTO `branch_details` (`id`, `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng`, `dt`, `user`, `updated`, `updated_by`, `mapto`, `flag`) VALUES (NULL, '".$_POST['bcode']."', '".$_POST['bname']."', '".$_POST['bfd']."', '".$_POST['region']."', '".$_POST['bm']."', '".$_POST['am']."', '".$_POST['rm']."', current_timestamp(), '".$_SESSION['loginuserid']."', '0', '0', '0', '0')";
    if($obj->getquerystr($qry));
       $msg = 'Record Added Successfully.';
  }
 ?>
