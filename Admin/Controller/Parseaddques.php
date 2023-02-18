<?php
  if(isset($_POST['submit']))
  {
    include_once '../Config/Mysql/conn.php';
    $msg='';
    $obj = new Connection();
    if(isset($_POST['sid']) && isset($_POST['ques']) && isset($_POST['weight']) && isset($_POST['score']))
    {
      $qry = "INSERT INTO `question` (`id`, `catog`, `sub_catog`, `ques_serial`, `ques`, `weightage`, `score`, `dt`, `flag`, `user`) VALUES (NULL, '".$_POST['sid']."', '".$_POST['sidsub']."', '".$_POST['quesserial']."', '".$_POST['ques']."', '".$_POST['weight']."', '".$_POST['score']."', current_timestamp(), '0', '".$_SESSION['loginuserid']."')";
      if($obj->getquerystr($qry))
      {
        $msg = array('Your Record Added Successfully','success');
      }
    }
   }
 ?>
