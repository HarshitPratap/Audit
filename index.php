<?php
	 include_once './Config/Session.php';
	 include_once './Config/Utilities.php';
	if(isset($_POST['submit']))
	{
		$msg='';
		// var_dump($_POST);
		// die();
		// $curl = curl_init();
		// curl_setopt_array($curl, [
		// 	CURLOPT_RETURNTRANSFER => 1,
		// 	CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
		// 	CURLOPT_POST => 1,
		// 	CURLOPT_POSTFIELDS => [
		// 		'secret' => '6LemXMUUAAAAAFcHFD7mo7mivqK8OyN05ZC8rYKP',
		// 		'response' => $_POST['g-recaptcha-response']
		// 	]
		// ]);
		// $response = json_decode(curl_exec($curl));
		// if (!$response->success) {
		// 		echo '<script>
		// 				alert("Please confirm you are not a robot.");
		// 		</script>';
		// } else
		{
			// code...
						include_once './Config/Mysql/conn.php';
						$obj = new Connection();
						$pass = md5($_POST['psw']);
						$user = $_POST['uname'];
						if(substr($user,0,3) == 'VIS')
						{
								$qry = "SELECT * FROM `visitor_dtls` WHERE `username`='".$user."' AND `login_pass` = '".$pass."' AND `flag` = '0'";
								$result = $obj->getquerystr($qry);
								$res = mysqli_fetch_row($result);
								if(mysqli_num_rows($result) > 0 && !strcmp($res[9],$user))
								{
									$_SESSION['login'] = 'yes';
									$_SESSION['loginuser'] = $res[9];
									$_SESSION['loginuserid'] = $res[0];
									$_SESSION['usertype'] = 'Visitor';
									$fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
									$_SESSION['last_active'] = time();
									$_SESSION['fingerprint'] = $fingerprint;
									$obj->getquerystr("INSERT INTO `login_dtls`(`id`, `visitor_id`, `dt`, `flag`) VALUES (NULL,'".$res[9]."',current_timestamp(),'0')");
									header("Location: Visitor/index.php");

								}
								else
								{
									$msg = 'Invalid Credentials';
								}
						}
						else
						{
								$qry = "SELECT * FROM `users` WHERE `user` = '".$user."' AND `pass` = '".$pass."' AND `flag` = '0'";
								$result = $obj->getquerystr($qry);
								$res = mysqli_fetch_row($result);
								if(mysqli_num_rows($result) > 0 && !strcmp($res[1],$user))
								{
									$_SESSION['login'] = 'yes';
									$_SESSION['loginuser'] = $res[1];
									$_SESSION['loginuserid'] = $res[0];
									$_SESSION['usertype'] = 'Admin';
									$fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
									$_SESSION['last_active'] = time();
									$_SESSION['fingerprint'] = $fingerprint;
									$obj->getquerystr("INSERT INTO `login_dtls`(`id`, `visitor_id`, `dt`, `flag`) VALUES (NULL,'".$res[1]."',current_timestamp(),'0')");
									header("Location: Admin/index.php");

								}
								else
								{
									$msg = 'Invalid Credentials';
								}
							}
					}
				}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>User Login</title>
 <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
*{
margin:0;
padding:0;
}

body,html {font-family: Arial, Helvetica, sans-serif;
background-image:url(Image/11.jpg);
background-repeat:no-repeat;
background-position: center;
background-size:cover;
height:100%;
width:100%;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
.button {
  background-color: #D51C22;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

.button:hover {
  opacity: 0.8;
}

/* Extra styles for the cancel button */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the image and position the close button */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  position: relative;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* The Modal (background) */
.modal {
  /*display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;

  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 40%; /* Could be more or less, depending on screen size */
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)}
  to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
  from {transform: scale(0)}
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<div id="id01" class="modal">

  <form class="modal-content animate" action="#" method="post" enctype="multipart/form-data">
    <div class="imgcontainer">
      <img src="Image/img_avatar.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
		    <!-- <div class="g-recaptcha" data-sitekey="6LemXMUUAAAAAN3G9eBlwEZbUcFnR8Gpa_g_SLo8"></div> -->
       <input type="submit" name="submit" class="button" value="Login" />
	 	 	 <span style="font-weight:bold;color:#D51C00;"><?php echo isset($msg)?$msg:'';?></span>
    </div>
  </form>
</div>
</body>
</html>
