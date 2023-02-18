<?php
  include_once '../Config/Mysql/conn.php';
  $obj = new Connection();
  function generatecode($con)
  {
    $s = 'VIS';
    $qry = "SELECT MAX(`id`) FROM `visitor_dtls`";
    $getmax = $con->getquerystr($qry);
    $id = mysqli_fetch_row($getmax);
    $id = $id[0]+1;
    $id1 = strlen((string)$id);
    switch($id1)
    {
      case 1:
        $id1 = '000'.$id;
        break;
      case 2:
        $id1 = '00'.$id;
        break;
      case 3:
        $id1 = '0'.$id;
        break;
      case 4:
        $id1 = $id;
        break;
      default:
        $id1 = '';
        break;
    }
    $s = $s.$id1;
    return $s;
  }
  if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $msg = '';
    $id = generatecode($obj);
    $name = trim($_POST['fname'].' '.$_POST['mname'].' '.$_POST['lname']);
    $qry = "INSERT INTO `visitor_dtls` (`id`, `name`, `addr`, `dist`, `state`, `phone`, `email`, `aadhar`, `pan`, `username`, `password`, `login_pass`, `user`, `flag`, `dt`) VALUES (NULL, '".$name."', '".$_POST['addr']."', '".$_POST['dist']."', '".$_POST['state']."', '".$_POST['cont']."', '".$_POST['emid']."', '".$_POST['anum']."', '".$_POST['pan']."', '".$id."', '".$id."', '".md5($id)."', '".$_SESSION['loginuserid']."', '0', current_timestamp())";
    if($obj->getquerystr($qry))
      $msg = 'User Created Successfully With Id '.$id .' And Password '.$id;
  }
 ?>
