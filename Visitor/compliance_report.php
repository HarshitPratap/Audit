<?php
  $page_title = "Audit | Visit | Compliance Report";
  include_once 'Includes/header.php';
  include("../Config/Mysql/conn.php");
  //include("../Config/Session.php");
  $obj = new Connection();
  //$brnch_id = "10:34:01";
  $table_data = "";
  ?>
  <script type="text/javascript">
    $(document).ready(function (){
      $.ajax({
        method:'POST',
        url:'data_files.php',
        data:{
          'block' : 'get_branch'
        },
        success:function(resp){
          var data = JSON.parse(resp);
          var out = "<option value=\"0\">Select Branch</option>";
          $.each(data, function(index, brnch) {
            out += "<option value=\"" + brnch.brn_code + "\">" + brnch.brn_code +" ( "+ brnch.brn_name + " )</option>";
          });
          $('#branch_id').html(out);
        },
        error:function(err){
          console.log("ERROR : " + err);
        }
      });
    });
  </script>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Compliance Report</h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <form enctype="application/x-www-form-urlencoded" action="#" method="post" id="comp_form">
              <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                  <div class="form-group">
                        <label for="branch_id">Select Branch</label>
                        <select id="branch_id" name="branch_id" class="form-control">
                        </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                  <button class="btn btn-outline-primary mt-lg-4 mt-md-4 mt-sm-0" type="submit" name="view_report" id="view_report">View Report</button>
  							</div>
            </div>
            </form>
          </div>
        </div>
        <?php if (isset($_POST['view_report'])):
            $brnch_id = $_POST['branch_id'];
           ?>
        <div class="table-responsive">
          <table class="w-100 table table-sm table-bordered table-striped">
            <?php
                $qry = "SELECT `brn_code`, `brn_name`, `brn_mng`, `reg_name`, `reg_mng`, `auditors_name`, `audit_eval_per_frm`, `to_date` FROM `branch_details` WHERE `brn_code` = '{$brnch_id}'";
                $result = $obj->getquerystr($qry);
                $resultset = mysqli_fetch_row($result);
                $table_data .= "<tr class=\"table-danger text-dark\"><td align=\"center\" colspan = \"8\">Branch Audit Compliance</td></tr>";
                $table_data .= "<tr>
                <td>Branch Name</td><td>{$resultset[0]}/{$resultset[1]}</td>
                <td>Branch Manager</td><td>{$resultset[2]}</td>
                <td>Region Name</td><td>{$resultset[3]}</td>
                <td>Regional Manager</td><td>{$resultset[4]}</td></tr>
                <tr>
                <td>Auditor Name</td><td>{$resultset[5]}</td>
                <td>Territory Manager</td><td></td>
                <td>Audit Evaluation Period Start</td><td>{$resultset[6]}</td>
                <td>End</td><td>{$resultset[7]}</td></tr>
                <tr>
                <td>Date Report Submitted by Audit Team</td><td></td>
                <td>Due Date for Compliance by Ops</td><td></td>
                <td>Date of Compliance Submission by Ops</td><td></td>
                <td>Date of Audit Compliance Verification</td><td></td></tr>";
                echo $table_data;
             ?>
          </table>
          <table class="w-100 table table-sm table-bordered table-striped">
            <thead>
              <tr>
                <th scope="col">Ques Sr.</th>
                <th scope = "col">Issue to resolve (Compliance)</th>
                <th scope = "col">Verifying officer (Operation dept)</th>
                <th scope = "col">Date of verification (center/client visit date and Time)</th>
                <th scope = "col">What is the action taken?</th>
                <th scope = "col">Timeline for resolution</th>
                <th scope = "col">Signature of the compliant</th>
              </tr>
            </thead>
            <tbody id="out">
              <?php
                  $table_data = "";
                  $qry = "SELECT DISTINCT(`catogory`.`id`),`catogory`.`catog` FROM `catogory` INNER JOIN `answer` ON `answer`.`cid` = `catogory`.`id` WHERE `answer`.`brnch` = '{$brnch_id}'";
                  $catog_result = $obj->getquerystr($qry);
                  while ($catog_result_set = mysqli_fetch_row($catog_result)) {
                    //print_r($catog_result_set);
                    //echo "<br/>";
                    $table_data .= "<tr class=\"table-danger text-dark\"><td colspan = \"7\">{$catog_result_set[1]}</td></tr>";
                    $qry = "SELECT DISTINCT(`subcatogory`.`id`),`subcatogory`.`subcatog` FROM `subcatogory` INNER JOIN `answer` ON `answer`.`scid` = `subcatogory`.`id` WHERE `answer`.`brnch` = '{$brnch_id}' AND `cato` = {$catog_result_set[0]}";
                    $sub_catog_result = $obj->getquerystr($qry);
                    while ($sub_catog_result_set = mysqli_fetch_row($sub_catog_result)) {
                        //print_r($sub_catog_result_set);
                        //echo "<br/>";
                        $table_data .= "<tr class=\"table-info text-dark\"><td colspan = \"7\">{$sub_catog_result_set[1]}</td></tr>";
                        if ($catog_result_set[0] == 4 && ($sub_catog_result_set[0] == 10 || $sub_catog_result_set[0] == 11 || $sub_catog_result_set[0] == 12 || $sub_catog_result_set[0] == 13)) {
                          //echo "{$catog_result_set[0]}. {$catog_result_set[1]} ........=> {$sub_catog_result_set[0]}. {$sub_catog_result_set[1]}<br/>";
                          $qry = "SELECT `id`,`centre_no`,`vill_name`,`cro_name` FROM `branch_operation_anxe` WHERE `branch_id` = '{$brnch_id}' AND `scid` = {$sub_catog_result_set[0]}";
                          $result_anex_id = $obj->getquerystr($qry);
                          while($anex_id_result_set = mysqli_fetch_row($result_anex_id)) {

                            if ($sub_catog_result_set[0] == 10 || $sub_catog_result_set[0] == 11) {
                              $table_data .= "<tr class=\"table-warning text-dark\"><td align=\"center\" colspan = \"7\">{$anex_id_result_set[2]}({$anex_id_result_set[3]})</td></tr>";
                            }elseif ($sub_catog_result_set[0] == 12 || $sub_catog_result_set[0] == 13) {
                              $table_data .= "<tr class=\"table-warning text-dark\"><td align=\"center\" colspan = \"7\">{$anex_id_result_set[1]}</td></tr>";
                            }
                            //print_r($anex_id_result_set);
                            //echo "<br/>";
                            $qry = "SELECT `question`.`ques_serial`, `question`.`ques`, `question`.`comp` FROM `question` INNER JOIN `answer` ON `question`.`id` = `answer`.`quid` WHERE `answer`.`cid` = 4 AND `answer`.`scid` = {$sub_catog_result_set[0]}
                            AND `answer`.`avg` = 0 AND `answer`.`anex_id` = {$anex_id_result_set[0]}";
                            $result_avg_anex_id = $obj->getquerystr($qry);
                            $num_rows_anex = mysqli_num_rows($result_avg_anex_id);
                            while ($avg_anex_id_result_set = mysqli_fetch_row($result_avg_anex_id)) {
                              //print_r($avg_anex_id_result_set);
                              //echo "<br/>";
                              $table_data .= "<tr><td scope=\"col\">{$avg_anex_id_result_set[0]}</td><td>{$avg_anex_id_result_set[2]}</td><td></td><td></td><td></td><td></td><td></td></tr>";
                            }
                          }
                        }else {
                          //echo "{$catog_result_set[0]}. {$catog_result_set[1]} => {$sub_catog_result_set[0]}. {$sub_catog_result_set[1]}<br/>";
                          $qry = "SELECT `question`.`ques_serial`, `question`.`ques`, `question`.`comp` FROM `question` INNER JOIN `answer` ON `question`.`id` = `answer`.`quid` WHERE `answer`.`cid` = {$catog_result_set[0]}
                          AND `answer`.`scid` = {$sub_catog_result_set[0]}
                          AND `answer`.`brnch` = '{$brnch_id}' AND (`answer`.`answ` = 'No' OR `answer`.`answ` = '4')";
                          $answer_result = $obj->getquerystr($qry);
                          while($answer_result_set = mysqli_fetch_row($answer_result)) {
                            //print_r($answer_result_set);
                            //echo '<br/>';
                            $table_data .= "<tr><td scope=\"col\">{$answer_result_set[0]}</td><td>{$answer_result_set[2]}</td><td></td><td></td><td></td><td></td><td></td></tr>";
                          }
                        }
                    }
                  }
                  echo $table_data;
                ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
     </div>
   </div>
  </div>
  <?php
    include_once 'Includes/footer.php';
    ?>
