<?php
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
  $msg = '';
  include_once '../Config/Mysql/conn.php';
  $obj = new Connection();

  $chk = $_POST['users'];
  $visitor = $_POST['siv'];
  $qry = "SELECT `id` FROM `visitor_dtls` WHERE `username` = '".$visitor."'";
  $result = $obj->getquerystr($qry);
  $res = mysqli_fetch_row($result);
  foreach($chk as $d)
  {
    $obj->getquerystr("UPDATE `branch_details` SET `mapto`='".$res[0]."' WHERE `brn_code` = '".$d."'");
    $msg = $d;
  }
}
 ?>
