<?php
    if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
    {
      include_once '../Config/Mysql/conn.php';
      $msg='';
      $obj = new Connection();
      $str = $_POST['compli'];
      $comp = htmlentities($str);
      $qry = "UPDATE `question` SET `catog`='".$_POST['sid']."',`sub_catog`='".$_POST['sidsub']."',`ques_serial`='".$_POST['quesserial']."',`ques`='".$_POST['ques']."', `comp` = '".$comp."', `weightage`='".$_POST['weight']."',`score`='".$_POST['score']."',`dt`=current_timestamp(),`user`='".$_SESSION['loginuserid']."' WHERE `id` = '".$_POST['qid']."'";
      if($obj->getquerystr($qry)) {
        $msg = array('Your Record Added Successfully','success');
      }else {
        $msg = array('Something went wrong.','danger');
      }
     }
   ?>
