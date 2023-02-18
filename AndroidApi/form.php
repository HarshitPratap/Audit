<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php
	echo($_SERVER['REMOTE_ADDR'].','. $_SERVER['HTTP_USER_AGENT']);
?>
<form action="visitor_login.php?pg=login" method="post" enctype="multipart/form-data">
	<input type="text" name="user"/>username<br/>
	<input type="password" name="pass"/>Password<br/>
	<input type="submit" name="submit" value="Send" />
</form>
</body>
</html>
