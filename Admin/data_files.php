<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['block']))
    {
        include("../Config/Mysql/conn.php");
        $obj = new Connection();
        $data = array();
        // $_POST['block'] = "brn_dtls";
        // // $_POST['cato'] = 1 ;
        switch ($_POST['block']) {
          case 'brn_dtls':
            $data = array();
            $sql = "SELECT  `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng` FROM `branch_details`";
            $result = $obj->getquerystr($sql);
            $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
            echo json_encode($data);
            // while($res = mysqli_fetch_row($result,MYSQLI_ASSOC))
            // {
            //   $ser = array('sr' => $i++);
            //   $res = array_merge($ser, $res);
            //
            // }
            // $data = array('data' => $data);

            // while($res = mysqli_fetch_array($result,MYSQLI_ASSOC))
            // {
            //   $qry1 = "SELECT `username`,`name` FROM `visitor_dtls` WHERE `id` = '".$res['mapto']."'";
      			// 	$getvisi = $obj->getquerystr($qry1);
            //   if(mysqli_num_rows($getvisi) > 0)
            //   {
            //     $visitor = mysqli_fetch_row($getvisi);
            //     $vis = array('mapto' => $visitor[0], 'visitor' => $visitor[1] );
            //     $res = array_merge($res, $vis);
            //   }
            //   else {
            //     $vis = array('mapto' => 'N/A', 'visitor' => 'N/A' );
            //     $res = array_merge($res, $vis);
            //   }
            //   array_push($data,$res);
            //}
            //echo json_encode($data);
          break;
          case 'visitors':
            $sql = "SELECT `name`, `addr`, `dist`, `state`, `phone`, `email`, `username` FROM `visitor_dtls` WHERE `flag` = '0'";
            $result = $obj->getquerystr($sql);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($data);
            break;
          case 'mapping':
              $qry ="SELECT `username`,`name` FROM `visitor_dtls` WHERE `flag` = '0'";
              $result = $obj->getquerystr($qry);
              $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
              $brn='';
              $qry = "SELECT  `brn_code`, `brn_name`, `brn_for_date`, `reg_name`, `brn_mng`, `area_mng`, `reg_mng` FROM `branch_details` WHERE `flag` ='0' AND `mapto` = '0'";
              $result = $obj->getquerystr($qry);
              $brn = mysqli_fetch_all($result, MYSQLI_ASSOC);
              $data = array('visitors' => $res, 'branches' => $brn);
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
              //echo json_encode($_POST);
              $qry = "SELECT  `id`, `ques`, `ques_serial`, `weightage`, `score` FROM `question` WHERE `catog` = '".$_POST['cato']."' AND `sub_catog` = '".$_POST['subcato']."'";
              $result = $obj->getquerystr($qry);
              while($res = mysqli_fetch_array($result,MYSQLI_ASSOC))
              {
                $id = array('id' => base64_encode("encodeuserid{$res['id']}encodeuserid{$res['ques']}"));
                $res = array_merge($res, $id);
      					array_push($data,$res);
              }
              echo json_encode($data);
              break;
          case 'unmaped_ques':
                  $qry = "SELECT  `id`, `ques`, `ques_serial`, `weightage`, `score` FROM `question` WHERE `catog` = '".$_POST['cato']."' AND `sub_catog` = '0'";
                  $result = $obj->getquerystr($qry);
                  while($res = mysqli_fetch_array($result,MYSQLI_ASSOC))
                  {
                    $id = array('id' => base64_encode("encodeuserid{$res['id']}encodeuserid{$res['ques']}"));
                    $res = array_merge($res, $id);
          					array_push($data,$res);
                  }
                  echo json_encode($data);
                  break;
          case 'editques':
              $id = $_POST['quesid'];
              $id = base64_decode($id);
              $id = explode("encodeuserid",$id);
              $qry = "SELECT * FROM `question` WHERE `id` = '".$id[1]."'";
              $result = $obj->getquerystr($qry);
              $res = mysqli_fetch_all($result,MYSQLI_ASSOC);
              $qry = "SELECT `id`, `subcatog` FROM `subcatogory` WHERE `cato` = '".$res[0]['catog']."' order by `id` asc";
              $result = $obj->getquerystr($qry);
              $subcatog = mysqli_fetch_all($result, MYSQLI_ASSOC);
              $qry = "SELECT `id`, `catog` FROM `catogory` order by `id` asc";
              $result = $obj->getquerystr($qry);
              $catog = mysqli_fetch_all($result, MYSQLI_ASSOC);
              $data = array('ques' => $res, 'cato' => $catog, 'subcato' => $subcatog);
              echo json_encode($data);
            break;
          case 'delete_ques':
              $id = $_POST['id'];
              $id = base64_decode($id);
              $id = explode("encodeuserid",$id);
              $qry = "DELETE FROM `question` WHERE `question`.`id` = '".$id[1]."'";
              if($obj->getquerystr($qry))
              {
                echo "Question Deleted Successfully.";
              }
            break;
          case 'delete_ques_all':
              $count = 0;
              $ques = $_POST['data'];
              foreach ($ques as $queid) {
                $id = base64_decode($queid);
                $id = explode("encodeuserid",$id);
                $qry = "DELETE FROM `question` WHERE `question`.`id` = '".$id[1]."'";
                if($obj->getquerystr($qry))
                {
                  $count++;
                }
              }
              echo $count." Question Deleted Successfully.";
            break;
          default:
            // code...
            break;
        }

    }
 ?>
