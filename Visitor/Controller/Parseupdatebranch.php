<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  include_once '../../Config/Session.php';
  include_once "../../Config/Mysql/conn.php";
  $obj = new Connection();
  $msg = '';
  $qry = "UPDATE `branch_details` SET `auditors_name`='".$_POST['adn']."',`brn_size`='".$_POST['bsize']."',`audit_start_date`='".$_POST['asd']."',`audit_end_date`='".$_POST['aed']."',`total_days_to_complete`='".$_POST['dtcmp']."',`publ_date`='".$_POST['pubd']."',
  `audit_eval_per_frm`='".$_POST['aepf']."',`to_date`='".$_POST['aeptd']."',`tot_date_inperi`='".$_POST['tdip']."',`rpt_code`='".$_POST['rcode']."',`sta_date`='".$_POST['sedate']."',`loan_out_acc`='".$_POST['loacc']."',
  `tot_port_acc`='".$_POST['tfo']."',`tot_cen_inbrc`='".$_POST['tcen']."',`acc_per_cen`='".$_POST['apc']."',`tot_par_client`='".$_POST['tpc']."',`par_outs`='".$_POST['pout']."',`port_at_risk`='".$_POST['parisk']."',
  `tot_cen_visitd`='".$_POST['tcv']."',`tot_client_in visited_cen`='".$_POST['tccv']."',`no_of_tot_cen_dis_in_las_qua`='".$_POST['noscd']."',`no_of_new_dis_cen_vis_dur_audit`='".$_POST['nosnd']."',
  `dt` = current_timestamp(),`updated`= '1' ,`updated_by`='".$_SESSION['loginuserid']."' WHERE `brn_code` ='".$_POST['bcode']."'";
   if($obj->getquerystr($qry))
     $msg = array('msg' => 'Record Updated Successfully.', 'class' => "alert alert-success");
    else {
       $msg = array('msg' => 'Record Not Updated .', 'class' => "alert alert-danger");
    }
}
 echo json_encode($msg);
 ?>
