<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['branch_id']))
{
    include("../Config/Mysql/conn.php");
    include("../Config/Session.php");
    $obj = new Connection();
    $jsdata = '';
    $arr_catog = array();
    $arr_catog_weig = array();
    $arr_final_sum_score = array();
    $arr_final_sum_weight = array();
    $qry = "SELECT DISTINCT(`cid`) FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' ORDER BY `cid` ASC";
    $result = $obj->getquerystr($qry);
    // loop start for selecting catogory from anser table which is answered by user
    while($cat = mysqli_fetch_row($result))
    {
      $arr_weight = array();
      $arr_score = array();
      $i = 0;
      // getting catogory and weightage from data base
      $qry = "SELECT `catog`, `Weightage` FROM `catogory` WHERE `id` = '".$cat[0]."'";
      $result_cat = $obj->getquerystr($qry);
      $catog = mysqli_fetch_row($result_cat);
      //selecting sub catogory from sub catogory from answer to divide section wise
      $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">".$catog[0]."</td></tr>";
      $qry = "SELECT DISTINCT(`scid`) FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '".$cat[0]."' ORDER BY `scid` ASC";
      $result_sub_cat = $obj->getquerystr($qry);
      $num_rows_sub_cat_answered = mysqli_num_rows($result_sub_cat);
      while($sub_cat = mysqli_fetch_row($result_sub_cat))
      {
        $qry = "SELECT `quid` FROM `answer` WHERE `answ` != 'N/A' AND `cid` = '".$cat[0]."' AND `scid` = '".$sub_cat[0]."' AND `brnch` = '".$_POST['branch_id']."'  ORDER BY `quid` ASC";
        $result_qid = $obj->getquerystr($qry);
        $qid_arr = mysqli_fetch_all($result_qid, MYSQLI_NUM);
        $str ='';
        foreach ($qid_arr as $arr) {
          $str .= $arr[0].',';
        }
        $str = trim($str,',');
        if ($cat[0] != 7 && !empty($str)) {
          $qry = "SELECT SUM(`weightage`) FROM `question` WHERE `id` IN (".$str.")";
          $result_weightage = $obj->getquerystr($qry);
          $sum_weightage = mysqli_fetch_row($result_weightage);
        }
        $sr = 1;
        $sum_score = 0;
        $qry = "SELECT COUNT(`id`) FROM `question` WHERE `catog` = ".$cat[0]." AND `sub_catog` = ".$sub_cat[0]."";
        $results_ques = $obj->getquerystr($qry);
        $num_rows_ques = mysqli_fetch_row($results_ques);
        if ($cat[0] == 7) {
          $qry = "SELECT DISTINCT(`quid`), `answ`, `avg`, `anex_id` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '".$cat[0]."' AND `scid` = '".$sub_cat[0]."' ORDER BY `ques_ser` ASC";
        }else {
          $qry = "SELECT DISTINCT(`quid`), `answ`, `cmnt` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '".$cat[0]."' AND `scid` = '".$sub_cat[0]."' ORDER BY `ques_ser` ASC";
        }
        $result_data = $obj->getquerystr($qry);
        $num_rows_data =mysqli_num_rows($result_data);
        if ($num_rows_data == $num_rows_ques[0]) {
          $qry = "SELECT `subcatog` FROM `subcatogory` WHERE `id` = '".$sub_cat[0]."'";
          $result_sub_cato = $obj->getquerystr($qry);
          $sub_cato = mysqli_fetch_row($result_sub_cato);
          $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">".$sub_cato[0]."</td></tr>";
        }
        while($data = mysqli_fetch_row($result_data))
        {
          $jsdata .= '<tr>';
          $qry = "SELECT `ques`, `weightage` FROM `question` WHERE `id` = '".$data[0]."'";
          $result_ques = $obj->getquerystr($qry);
          if ($num_rows_data == $num_rows_ques[0]) {
            $ques_data = mysqli_fetch_row($result_ques);
            if ($data[0] == 83) {
              $val2 = $ques_data[1];
              if ($data[1] == 1) {
                $val = "<30%";
                $val1 = round(($ques_data[1]/($sum_weightage[0]/100))/10,2);
              }elseif ($data[1] == 2) {
                $val = "30% - 35%";
                $val1 = round((( $ques_data[1] * 0.75 )/($sum_weightage[0]/100))/10,2);
              }elseif ($data[1] == 3) {
                $val = "35.1% - 50%";
                $val1 = round((( $ques_data[1] * 0.50 )/($sum_weightage[0]/100))/10,2);
              }elseif ($data[1] == 4) {
                $val = ">50%";
                $val1 = 0;
              }
                $jsdata .= '<td>'.$sr++.'</td><td>'.$ques_data[0].'</td><td>'.$val.'</td><td>'.$val2.'</td><td>'.$val1.'</td><td>'.$data[2].'</td>';
                $jsdata .= '</tr>';
            }elseif ($cat[0] == 7) {
              if ($sub_cat[0] == 19) {
                if($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes' || $data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No'){
                    $val = $ques_data[1];
                    $risk_weight = 6;
                }
                elseif($data[1] == 'N/A' || $data[1] == 'n/a'){
                    $val = 0;
                    $risk_weight = 0;
                }
                $impact = (($data[3]/$data[2])*100);
                if ($data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No') {
                  $val1 = 6;
                }elseif ($data[1] == 'N/A' || $data[1] == 'n/a') {
                  if ($data[2] == $data[3]) {
                    $val1 = 0;
                  }
                }elseif ($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes') {
                  if ($data[3] == 0) {
                    $val1 = 6;
                  }elseif ($impact >= 105) {
                    $val1 = -(6 * 0.4);
                  }elseif ($impact > 100) {
                    $val1 = -(6 * 0.2);
                  }elseif ($data[2] == 0) {
                    if ($data[2] < $data[3]) {
                      $val1 = -(6 * 0.2);
                    }
                  }elseif ($impact < 95) {
                    $val1 = (6 * 0.6);
                  }elseif ($impact < 100) {
                    $val1 = (6 * 0.4);
                  }else {
                    $val1 = 0;
                  }
                }
                $jsdata .= '<td>'.$sr++.'</td><td>'.$ques_data[0].'</td><td>'.$data[1].'</td><td>'.$val.'</td><td>'.$val1.'</td><td>'.$impact.'%</td></tr>';
                $jsdata .= '<tr><td colspan="3"></td><td>'.$data[2].'</td><td>'.$data[3].'</td><td></td></tr>';
              }elseif ($sub_cat[0]==20) {
                if($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes' || $data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No'){
                    $val = $ques_data[1];
                    $risk_weight = 6;
                }
                elseif($data[1] == 'N/A' || $data[1] == 'n/a'){
                    $val = 0;
                    $risk_weight = 0;
                }
                $impact = (($data[3]/$data[2])*100);
                if ($data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No') {
                  if ($impact < 100) {
                    $val1 = 0;
                  }
                }elseif ($data[1] == 'N/A' || $data[1] == 'n/a') {
                  if ($data[2] == 0) {
                    $val1 = 0;
                  }
                }elseif ($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes') {
                  if ($impact == 100) {
                    $val1 = 6;
                  }
                }
                $jsdata .= '<td>'.$sr++.'</td><td>'.$ques_data[0].'</td><td>'.$data[1].'</td><td>'.$val.'</td><td>'.$val1.'</td><td>'.$impact.'%</td></tr>';
                $jsdata .= '<tr><td colspan="3"></td><td>'.$data[2].'</td><td>'.$data[3].'</td><td></td></tr>';
              }elseif ($sub_cat[0] == 21) {
                if($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes' || $data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No'){
                    $val = $ques_data[1];
                    $risk_weight = 3;
                }
                elseif($data[1] == 'N/A' || $data[1] == 'n/a'){
                    $val = 0;
                    $risk_weight = 0;
                }
                $impact = ($data[2] == 0 ? 0 : ($data[2]*100));
                if ($data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No') {
                  if ($data[2] == 0) {
                    $val1 = 3;
                  }
                }elseif ($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes') {
                  if ($data[2] > 0) {
                    $val1 = -($data[2] * 1);
                  }
                }
                $jsdata .= '<td>'.$sr++.'</td><td>'.$ques_data[0].'</td><td>'.$data[1].'</td><td>'.$val.'</td><td>'.$val1.'</td><td>'.$impact.'%</td></tr>';
                $jsdata .= '<tr><td colspan="3"></td><td>'.$data[2].'</td><td></td><td></td></tr>';

              }
            }else {
              if($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes' || $data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No')
               $val = $ques_data[1];
              elseif($data[1] == 'N/A' || $data[1] == 'n/a')
               $val = 0;
              if($data[1] == 'N/A' || $data[1] == 'n/a' || $data[1] == 'NO' || $data[1] == 'no' || $data[1] == 'No')
               $val1 = 0;
              elseif($data[1] == 'YES' || $data[1] == 'yes' || $data[1] == 'Yes')
               $val1 = round(($ques_data[1]/($sum_weightage[0]/100))/10,2);
               if($cat[0] == 4 && ($sub_cat[0] == 10 || $sub_cat[0] == 11 || $sub_cat[0] == 12 || $sub_cat[0] == 13)){
                 $qry = "SELECT ROUND(AVG(`avg`),2) AS `avg` FROM `answer` WHERE `answ` = 'Yes' AND `quid` = ".$data[0]." AND `brnch` = '".$_POST['branch_id']."' AND `cid` = ".$cat[0]." AND `scid` = ".$sub_cat[0];
                 $result_avg = $obj->getquerystr($qry);
                 $avg = mysqli_fetch_row($result_avg);
                 $val1 = $val1 * $avg[0];
               }
               $jsdata .= '<td>'.$sr++.'</td><td>'.$ques_data[0].'</td><td>'.$data[1].'</td><td>'.$val.'</td><td>'.$val1.'</td><td>'.$data[2].'</td>';
               $jsdata .= '</tr>';
            }
            $sum_score += $val1;


          }
        }
        $arr_score[$i] =  round($sum_score,2);
        if ($cat[0] != 7) {
          $arr_weight[$i++] = round($sum_weightage[0]);
        }else {
          $arr_weight[$i++] = round($risk_weight);
        }
        if ($num_rows_data == $num_rows_ques[0]) {
          //$jsdata .= '<tr><td colspan="6"></td></tr>';
          $jsdata .= '<tr class="table-info text-dark"><td colspan="3">Total</td><td>'.round($sum_weightage[0]).'</td><td colspan="2">'.round($sum_score,2).'</td></tr>';
          $jsdata .= '<tr><td colspan="6"></td></tr>';
        }
        if ($cat[0] == 4) {
          if ($sub_cat[0] == 10) {
            $qry = "SELECT `id`, `dated`, `vill_name`,`tot`,`tot1`,`cro_name` FROM `branch_operation_anxe` WHERE `scid` = '10' AND `branch_id` = '".$_POST['branch_id']."'";
            $result_cgt = $obj->getquerystr($qry);
            $anex_cgt = mysqli_fetch_all($result_cgt,MYSQLI_NUM);
            $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">CGT Annexure</td></tr>";
            $jsdata .= "<tr><td>#</td><td>Details</td><td>1</td><td>2</td><td>3</td><td>Average</td></tr>";
            $jsdata .= "<tr><td>1</td><td>Date of CGT Visited</td><td>".(isset($anex_cgt[0][1])?$anex_cgt[0][1]:'')."</td><td>".(isset($anex_cgt[1][1])?$anex_cgt[1][1]:'')."</td><td>".(isset($anex_cgt[2][1])?$anex_cgt[2][1]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>2</td><td>Village</td><td>".(isset($anex_cgt[0][2])?$anex_cgt[0][2]:'')."</td><td>".(isset($anex_cgt[1][2])?$anex_cgt[1][2]:'')."</td><td>".(isset($anex_cgt[2][2])?$anex_cgt[2][2]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>3</td><td>Total Members present in CGT</td><td>".(isset($anex_cgt[0][3])?$anex_cgt[0][3]:'')."</td><td>".(isset($anex_cgt[1][3])?$anex_cgt[1][3]:'')."</td><td>".(isset($anex_cgt[2][3])?$anex_cgt[2][3]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>Total Members in visited /presentCGT</td><td>".(isset($anex_cgt[0][4])?$anex_cgt[0][4]:'')."</td><td>".(isset($anex_cgt[1][4])?$anex_cgt[1][4]:'')."</td><td>".(isset($anex_cgt[2][4])?$anex_cgt[2][4]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>CRO's Name</td><td>".(isset($anex_cgt[0][5])?$anex_cgt[0][5]:'')."</td><td>".(isset($anex_cgt[1][5])?$anex_cgt[1][5]:'')."</td><td>".(isset($anex_cgt[2][5])?$anex_cgt[2][5]:'')."</td><td></td></tr>";
            $sr_anex = 1 ;
            $qry = "SELECT `id`, `ques` FROM `question` WHERE `catog` = '4' AND `sub_catog` = '10'";
            $result_anex_cgt = $obj->getquerystr($qry);
            while($ques_anex_cgt = mysqli_fetch_row($result_anex_cgt)){
              if (isset($anex_cgt[0][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '10' AND `quid` = '".$ques_anex_cgt[0]."' AND `anex_id` = '".$anex_cgt[0][0]."'";
                $result_avg_anex_cgt = $obj->getquerystr($qry);
                $avg_anex_cgt1 = mysqli_fetch_row($result_avg_anex_cgt);
              }
              if (isset($anex_cgt[1][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '10' AND `quid` = '".$ques_anex_cgt[0]."' AND `anex_id` = '".$anex_cgt[1][0]."'";
                $result_avg_anex_cgt = $obj->getquerystr($qry);
                $avg_anex_cgt2 = mysqli_fetch_row($result_avg_anex_cgt);
              }
              if (isset($anex_cgt[2][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '10' AND `quid` = '".$ques_anex_cgt[0]."' AND `anex_id` = '".$anex_cgt[2][0]."'";
                $result_avg_anex_cgt = $obj->getquerystr($qry);
                $avg_anex_cgt3 = mysqli_fetch_row($result_avg_anex_cgt);
              }
              $qry = "SELECT ROUND(AVG(`avg`),2) AS `avg` FROM `answer` WHERE `answ` = 'Yes' AND `quid` = '".$ques_anex_cgt[0]."' AND `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '10'";
              $result_avg_cgt = $obj->getquerystr($qry);
              $final_avg_cgt = mysqli_fetch_row($result_avg_cgt);
              $jsdata .= "<tr><td>". $sr_anex++ ."</td><td>".$ques_anex_cgt[1]."</td><td>".(isset($avg_anex_cgt1[0])?$avg_anex_cgt1[0]:'')."</td><td>".(isset($avg_anex_cgt2[0])?$avg_anex_cgt2[0]:'')."</td><td>".(isset($avg_anex_cgt3[0])?$avg_anex_cgt3[0]:'')."</td><td>".$final_avg_cgt[0]."</td></tr>";
            }
            $jsdata .= "<tr><td colspan=\"6\"></td></tr>";
          }elseif ($sub_cat[0] == 11) {
            $qry = "SELECT `id`, `dated`, `vill_name`, `cro_name`, `grt_name` FROM `branch_operation_anxe` WHERE `scid` = '11' AND `branch_id` = '".$_POST['branch_id']."'";
            $result_grt = $obj->getquerystr($qry);
            $anex_grt = mysqli_fetch_all($result_grt,MYSQLI_NUM);
            $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">GRT Annexure</td></tr>";
            $jsdata .= "<tr><td>#</td><td>Details</td><td>1</td><td>2</td><td>3</td><td>Average</td></tr>";
            $jsdata .= "<tr><td>1</td><td>Date of GRT Visited</td><td>".(isset($anex_grt[0][1])?$anex_grt[0][1]:'')."</td><td>".(isset($anex_grt[1][1])?$anex_grt[1][1]:'')."</td><td>".(isset($anex_grt[2][1])?$anex_grt[2][1]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>2</td><td>Village</td><td>".(isset($anex_grt[0][2])?$anex_grt[0][2]:'')."</td><td>".(isset($anex_grt[1][2])?$anex_grt[1][2]:'')."</td><td>".(isset($anex_grt[2][2])?$anex_grt[2][2]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>3</td><td>CRO's Name</td><td>".(isset($anex_grt[0][3])?$anex_grt[0][3]:'')."</td><td>".(isset($anex_grt[1][3])?$anex_grt[1][3]:'')."</td><td>".(isset($anex_grt[2][3])?$anex_grt[2][3]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>GRT</td><td>".(isset($anex_grt[0][4])?$anex_grt[0][4]:'')."</td><td>".(isset($anex_grt[1][4])?$anex_grt[1][4]:'')."</td><td>".(isset($anex_grt[2][4])?$anex_grt[2][4]:'')."</td><td></td></tr>";
            $sr_anex = 1 ;
            $qry = "SELECT `id`, `ques` FROM `question` WHERE `catog` = '4' AND `sub_catog` = '11'";
            $result_anex_grt = $obj->getquerystr($qry);
            while($ques_anex_grt = mysqli_fetch_row($result_anex_grt)){
              if (isset($anex_grt[0][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '11' AND `quid` = '".$ques_anex_grt[0]."' AND `anex_id` = '".$anex_grt[0][0]."'";
                $result_avg_anex_grt = $obj->getquerystr($qry);
                $avg_anex_grt1 = mysqli_fetch_row($result_avg_anex_grt);
              }
              if (isset($anex_grt[1][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '11' AND `quid` = '".$ques_anex_grt[0]."' AND `anex_id` = '".$anex_grt[1][0]."'";
                $result_avg_anex_grt = $obj->getquerystr($qry);
                $avg_anex_grt2 = mysqli_fetch_row($result_avg_anex_grt);
              }
              if (isset($anex_grt[2][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '11' AND `quid` = '".$ques_anex_grt[0]."' AND `anex_id` = '".$anex_grt[2][0]."'";
                $result_avg_anex_grt = $obj->getquerystr($qry);
                $avg_anex_grt3 = mysqli_fetch_row($result_avg_anex_grt);
              }
              $qry = "SELECT ROUND(AVG(`avg`),2) AS `avg` FROM `answer` WHERE `answ` = 'Yes' AND `quid` = '".$ques_anex_grt[0]."' AND `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '11'";
              $result_avg_grt = $obj->getquerystr($qry);
              $final_avg_grt = mysqli_fetch_row($result_avg_grt);
              $jsdata .= "<tr><td>". $sr_anex++ ."</td><td>".$ques_anex_grt[1]."</td><td>".(isset($avg_anex_grt1[0])?$avg_anex_grt1[0]:'')."</td><td>".(isset($avg_anex_grt2[0])?$avg_anex_grt2[0]:'')."</td><td>".(isset($avg_anex_grt3[0])?$avg_anex_grt3[0]:'')."</td><td>".$final_avg_grt[0]."</td></tr>";
            }
            $jsdata .= "<tr><td colspan=\"6\"></td></tr>";
          }elseif ($sub_cat[0] == 12) {
            $qry = "SELECT `id`, `dated`, `centre_no`, `exe_clients`, `new_clients`, `cro_name` FROM `branch_operation_anxe` WHERE `scid` = '12' AND `branch_id` = '".$_POST['branch_id']."'";
            $result_loan = $obj->getquerystr($qry);
            $anex_loan = mysqli_fetch_all($result_loan,MYSQLI_NUM);
            $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">Loan Disbursement Annexure</td></tr>";
            $jsdata .= "<tr><td>#</td><td>Details</td><td>1</td><td>2</td><td>3</td><td>Average</td></tr>";
            $jsdata .= "<tr><td>1</td><td>Date of loan Disbursement</td><td>".(isset($anex_loan[0][1])?$anex_loan[0][1]:'')."</td><td>".(isset($anex_loan[1][1])?$anex_loan[1][1]:'')."</td><td>".(isset($anex_loan[2][1])?$anex_loan[2][1]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>2</td><td>Centre Number</td><td>".(isset($anex_loan[0][2])?$anex_loan[0][2]:'')."</td><td>".(isset($anex_loan[1][2])?$anex_loan[1][2]:'')."</td><td>".(isset($anex_loan[2][2])?$anex_loan[2][2]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>3</td><td>Existing clients</td><td>".(isset($anex_loan[0][3])?$anex_loan[0][3]:'')."</td><td>".(isset($anex_loan[1][3])?$anex_loan[1][3]:'')."</td><td>".(isset($anex_loan[2][3])?$anex_loan[2][3]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>New Clients</td><td>".(isset($anex_loan[0][4])?$anex_loan[0][4]:'')."</td><td>".(isset($anex_loan[1][4])?$anex_loan[1][4]:'')."</td><td>".(isset($anex_loan[2][4])?$anex_loan[2][4]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>CRO's Name</td><td>".(isset($anex_loan[0][5])?$anex_loan[0][5]:'')."</td><td>".(isset($anex_loan[1][5])?$anex_loan[1][5]:'')."</td><td>".(isset($anex_loan[2][5])?$anex_loan[2][5]:'')."</td><td></td></tr>";
            $sr_anex = 1 ;
            $qry = "SELECT `id`, `ques` FROM `question` WHERE `catog` = '4' AND `sub_catog` = '12'";
            $result_anex_loan = $obj->getquerystr($qry);
            while($ques_anex_loan = mysqli_fetch_row($result_anex_loan)){
              if (isset($anex_cgt[0][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '12' AND `quid` = '".$ques_anex_loan[0]."' AND `anex_id` = '".$anex_loan[0][0]."'";
                $result_avg_anex_loan = $obj->getquerystr($qry);
                $avg_anex_loan1 = mysqli_fetch_row($result_avg_anex_loan);
              }
              if (isset($anex_loan[1][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '12' AND `quid` = '".$ques_anex_loan[0]."' AND `anex_id` = '".$anex_loan[1][0]."'";
                $result_avg_anex_loan = $obj->getquerystr($qry);
                $avg_anex_loan2 = mysqli_fetch_row($result_avg_anex_loan);
              }
              if (isset($anex_loan[2][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '12' AND `quid` = '".$ques_anex_loan[0]."' AND `anex_id` = '".$anex_loan[2][0]."'";
                $result_avg_anex_loan = $obj->getquerystr($qry);
                $avg_anex_loan3 = mysqli_fetch_row($result_avg_anex_loan);
              }
              $qry = "SELECT ROUND(AVG(`avg`),2) AS `avg` FROM `answer` WHERE `answ` = 'Yes' AND `quid` = '".$ques_anex_loan[0]."' AND `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '12'";
              $result_avg_loan = $obj->getquerystr($qry);
              $final_avg_loan = mysqli_fetch_row($result_avg_loan);
              $jsdata .= "<tr><td>". $sr_anex++ ."</td><td>".$ques_anex_loan[1]."</td><td>".(isset($avg_anex_loan1[0])?$avg_anex_loan1[0]:'')."</td><td>".(isset($avg_anex_loan2[0])?$avg_anex_loan2[0]:'')."</td><td>".(isset($avg_anex_loan3[0])?$avg_anex_loan3[0]:'')."</td><td>".$final_avg_loan[0]."</td></tr>";
            }
            $jsdata .= "<tr><td colspan=\"6\"></td></tr>";
          }elseif ($sub_cat[0] == 13) {
            $qry = "SELECT `id`, `dated`, `centre_no`, `new_dis_cen`, `tot`, `tot1`, `cro_name` FROM `branch_operation_anxe` WHERE `scid` = '13' AND `branch_id` = '".$_POST['branch_id']."'";
            $result_centre = $obj->getquerystr($qry);
            $anex_centre = mysqli_fetch_all($result_centre,MYSQLI_NUM);
            $jsdata .= "<tr class=\"table-danger text-dark\"><td colspan=\"6\">Center Meeting Annexure</td></tr>";
            $jsdata .= "<tr><td>#</td><td>Details</td><td>1</td><td>2</td><td>3</td><td>Average</td></tr>";
            $jsdata .= "<tr><td>1</td><td>Date of Centre Meeting Visit</td><td>".(isset($anex_centre[0][1])?$anex_centre[0][1]:'')."</td><td>".(isset($anex_centre[1][1])?$anex_centre[1][1]:'')."</td><td>".(isset($anex_centre[2][1])?$anex_centre[2][1]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>2</td><td>Centre Number</td><td>".(isset($anex_centre[0][2])?$anex_centre[0][2]:'')."</td><td>".(isset($anex_centre[1][2])?$anex_centre[1][2]:'')."</td><td>".(isset($anex_centre[2][2])?$anex_centre[2][2]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>3</td><td>Is New disbursed centre visited during Audit</td><td>".(isset($anex_centre[0][3])?$anex_centre[0][3]:'')."</td><td>".(isset($anex_centre[1][3])?$anex_centre[1][3]:'')."</td><td>".(isset($anex_centre[2][3])?$anex_centre[2][3]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>Total Clients Present in  centres</td><td>".(isset($anex_centre[0][4])?$anex_centre[0][4]:'')."</td><td>".(isset($anex_centre[1][4])?$anex_centre[1][4]:'')."</td><td>".(isset($anex_centre[2][4])?$anex_centre[2][4]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>Total Clients in Visited centres</td><td>".(isset($anex_centre[0][5])?$anex_centre[0][5]:'')."</td><td>".(isset($anex_centre[1][5])?$anex_centre[1][5]:'')."</td><td>".(isset($anex_centre[2][5])?$anex_centre[2][5]:'')."</td><td></td></tr>";
            $jsdata .= "<tr><td>4</td><td>CRO's Name</td><td>".(isset($anex_centre[0][6])?$anex_centre[0][6]:'')."</td><td>".(isset($anex_centre[1][6])?$anex_centre[1][6]:'')."</td><td>".(isset($anex_centre[2][6])?$anex_centre[2][6]:'')."</td><td></td></tr>";
            $sr_anex = 1 ;
            $qry = "SELECT `id`, `ques` FROM `question` WHERE `catog` = '4' AND `sub_catog` = '13'";
            $result_anex_centre = $obj->getquerystr($qry);
            while($ques_anex_centre = mysqli_fetch_row($result_anex_centre)){
              if (isset($anex_centre[0][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '13' AND `quid` = '".$ques_anex_centre[0]."' AND `anex_id` = '".$anex_centre[0][0]."'";
                $result_avg_anex_centre = $obj->getquerystr($qry);
                $avg_anex_centre1 = mysqli_fetch_row($result_avg_anex_centre);
              }
              if (isset($anex_centre[1][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '13' AND `quid` = '".$ques_anex_centre[0]."' AND `anex_id` = '".$anex_centre[1][0]."'";
                $result_avg_anex_centre = $obj->getquerystr($qry);
                $avg_anex_centre2 = mysqli_fetch_row($result_avg_anex_centre);
              }
              if (isset($anex_cgt[2][0])) {
                $qry = "SELECT `avg` FROM `answer` WHERE `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '13' AND `quid` = '".$ques_anex_centre[0]."' AND `anex_id` = '".$anex_centre[2][0]."'";
                $result_avg_anex_centre = $obj->getquerystr($qry);
                $avg_anex_centre3 = mysqli_fetch_row($result_avg_anex_centre);
              }
              $qry = "SELECT ROUND(AVG(`avg`),2) AS `avg` FROM `answer` WHERE `answ` = 'Yes' AND `quid` = '".$ques_anex_centre[0]."' AND `brnch` = '".$_POST['branch_id']."' AND `cid` = '4' AND `scid` = '13'";
              $result_avg_centre = $obj->getquerystr($qry);
              $final_avg_centre = mysqli_fetch_row($result_avg_centre);
              $jsdata .= "<tr><td>". $sr_anex++ ."</td><td>".$ques_anex_centre[1]."</td><td>".(isset($avg_anex_centre1[0])?$avg_anex_centre1[0]:'')."</td><td>".(isset($avg_anex_centre2[0])?$avg_anex_centre2[0]:'')."</td><td>".(isset($avg_anex_centre3[0])?$avg_anex_centre3[0]:'')."</td><td>".$final_avg_centre[0]."</td></tr>";
            }
            $jsdata .= "<tr><td colspan=\"6\"></td></tr>";
          }
        }
      }
      $qry = "SELECT `subcatog`, `Weightage` FROM `subcatogory` WHERE `cato` = '".$cat[0]."'";
      $result_sub_cat = $obj->getquerystr($qry);
      $num_rows_sub_cat = mysqli_num_rows($result_sub_cat);
      if($num_rows_sub_cat == $num_rows_sub_cat_answered){
        $i = 0;
        $val = 0;
        $val1 = 0;
        $sum_val = 0;
        $sum_val1 = 0;
        array_push($arr_catog,$catog[0]);
        array_push($arr_catog_weig,$catog[1]);
        $jsdata .= '<tr class="table-warning text-dark"><td colspan="4">'.$catog[0].'</td><td>Weightage%</td><td>% Scored</td></tr>';
        while($sub_catog = mysqli_fetch_row($result_sub_cat))
        {
          if ($arr_weight[$i] > 0 ) {
            $val = (double)$sub_catog[1];
          }elseif ($arr_weight[$i] <= 0) {
            $val = 0;
          }
          $sum_val += $val;
          if ($cat[0] != 7) {
            $val1 = (( $arr_score[$i] * 10 * $val) / 100);
          }elseif ($cat[0] == 7) {
            $val1 = $arr_score[$i];
          }
          $sum_val1 +=$val1;
          $i++;
          $jsdata .= '<tr class="table-warning text-dark"><td colspan="4">'.$sub_catog[0].'</td><td>'.$val.'</td><td>'.$val1.'</td></tr>';
        }
        $jsdata .= '<tr class="table-warning text-dark"><td colspan="4">Total</td><td>'.$sum_val.'</td><td>'.$sum_val1.'</td></tr>';
        array_push($arr_final_sum_score,$sum_val1);
        array_push($arr_final_sum_weight,$sum_val);
        $jsdata .= '<tr rowspan="3"><td colspan="6"></td></tr>';
      }
    }

    $sum_val = 0;
    $sum_val1 = 0;
    $jsdata .= '<tr class="table-info text-dark"><td colspan="4">Catogory</td><td>Weightage%</td><td>% Scored</td></tr>';
    foreach ($arr_catog as $key => $catogory) {
      if ($catogory == "Risk (PAR/Audit Compliance/Fraud)") {
        $res_sum = ($arr_final_sum_score[$key] != 0 ) ? round($arr_final_sum_score[$key] * ($arr_catog_weig[$key]/$arr_final_sum_weight[$key]),2) : 0 ;
        //die();
      }else {
        $res_sum = ($arr_final_sum_score[$key] > 0 ) ? round($arr_catog_weig[$key] * ($arr_final_sum_score[$key]/100),2) : 0 ;
      }

      $jsdata .= '<tr class="table-info text-dark"><td colspan="4">'.$catogory.'</td><td>'.(( $arr_catog_weig[$key] > 0) ? $arr_catog_weig[$key] : 0).'</td><td>'.$res_sum.'</td></tr>';
      $sum_val += $arr_catog_weig[$key];
      $sum_val1 += $res_sum;
    }
    $jsdata .= '<tr class="table-info text-dark"><td colspan="4">Total</td><td>'.$sum_val.'</td><td>'.$sum_val1.'</td></tr>';
    echo $jsdata;
  }
 ?>
