<?php
  include_once '../Config/Session.php';
  include_once '../Config/Utilities.php';
  guard();
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
 <title><?php if(isset($page_title)) echo $page_title; ?></title>
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/> -->
 <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
 <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-grid.min.css">
 <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap-reboot.min.css">
 <link rel="stylesheet" href="../assets/bootstrap/css/mycss.css">
 <link rel="stylesheet" href="../assets/tether/tether.min.css">
 <link rel="stylesheet" href="../assets/dropdown/css/style.css">
 <link rel="stylesheet" href="../assets/theme/css/style.css">
 <link rel="preload" as="style" href="../assets/mobirise/css/mbr-additional.css">
 <link rel="stylesheet" href="../assets/mobirise/css/mbr-additional.css" type="text/css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
 <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->
 <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script> -->
 <script src="../assets/loader/jquery.ajaxloader.js"></script>
 <section class="menu cid-rFksAdfWAZ" once="menu" id="menu1-0">
 </head>

 <body>
      <nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm">
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <div class="hamburger">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
              </div>
          </button>
          <div class="menu-logo">
              <div class="navbar-brand">

                  <span class="navbar-caption-wrap">
          					<a class="navbar-caption text-white display-2" href="index.php">Save</a>
          				</span>

              </div>
          </div>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav nav-dropdown nav-right" data-app-modern-menu="true">
  				<li class="nav-item">
              <a class="nav-link link text-white display-4" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
          <a class="nav-link link text-white dropdown-toggle display-4" href="#" data-toggle="dropdown-submenu" aria-expanded="true">Services&nbsp;</a>
  					<div class="dropdown-menu">
  						<a class="text-white dropdown-item display-4" href="add_branch.php">Add Branch</a>
  						<a class="text-white dropdown-item display-4" href="add_ques.php" aria-expanded="false">Add Question</a>
  						<a class="text-white dropdown-item display-4" href="create_user.php" aria-expanded="false">Create Visitors</a>
  						<a class="text-white dropdown-item display-4" href="map_branch.php" aria-expanded="false">Map Branches</a>
  					</div>
          </li>
  				<li class="nav-item dropdown">
            <a class="nav-link link text-white dropdown-toggle display-4" href="#" data-toggle="dropdown-submenu" aria-expanded="true">Report&nbsp;</a>
  					<div class="dropdown-menu">
  						<a class="text-white dropdown-item display-4" href="branch_details.php">Branch Details</a>
  						<a class="text-white dropdown-item display-4" href="visitors_details.php" aria-expanded="false">Visitors Details</a>
  						<a class="text-white dropdown-item display-4" href="question_rpt.php" aria-expanded="false">Added Questions</a>
              <!-- <a class="text-white dropdown-item display-4" href="unmaped_question.php" aria-expanded="false">Map Sub-Catogory</a> -->
  					</div>
          </li>
  				<li class="nav-item dropdown">
            <a class="nav-link link text-white dropdown-toggle display-4" href="#" data-toggle="dropdown-submenu" aria-expanded="true"><?=$_SESSION['loginuser'];?></a>
  					<div class="dropdown-menu">
              <!-- <a class="text-white dropdown-item display-4" href="profile.php">Profile</a> -->
  						<a class="text-white dropdown-item display-4" href="logout.php">Logout</a>
  					</div>
          </li>
  				<!--<li class="nav-item">
  					<a class="nav-link link text-white display-4" href="logout.php" aria-expanded="false">Logout</a>
  				</li>-->
  		   </ul>

        </div>
      </nav>
  </section>
