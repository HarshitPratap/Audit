<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  include_once '../../Config/Session.php';
  include_once '../../Config/Mysql/conn.php';
  $msg = '';
  $obj = new Connection();
  $qry = "SELECT * FROM `visitor_dtls` WHERE `login_pass` = '".md5($_POST['cnf'])."' AND `username` = '".$_SESSION['loginuser']."'";
  $result = $obj->getquerystr($qry);
  if(mysqli_num_rows($result) == 1)
  {
    if($_POST['cnfnew'] == $_POST['newp'])
    {
      $qry = "UPDATE `visitor_dtls` SET `password`='".$_POST['cnfnew']."',`login_pass`='".md5($_POST['cnfnew'])."' WHERE `username` = '".$_SESSION['loginuser']."'";
      $result = $obj->getquerystr($qry);
      if($result)
       $msg = array('msg' => "Password Changed Successfully.",'class' => "alert alert-success");
    }
    else {
       $msg = array('msg' => 'New Password and Confirm New Password Does Not Match', 'class' => "alert alert-danger");
    }

  }
  else
  {
    $msg = array('msg' => "Current Password Does Not Matched.", 'class' => "alert alert-danger");
  }
  echo json_encode($msg);
}
?>
