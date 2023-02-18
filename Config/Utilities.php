<?php
function signout() {
  session_destroy();
  session_unset();
  redirectTo('../index');
}
function redirectTo($page) {
    header("Location: {$page}.php");
}
function guard() {
    $isValid = true;
    $inactive = 60 * 10; //10mins
    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    if(!isset($_SESSION['login']) && $_SESSION['login'] != 'yes') {
        $isValid = false;
        signout();
    }
    else if((isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] != $fingerprint)) {
        $isValid = false;
        signout();
    }
    else if((isset($_SESSION['last_active']) && (time() - $_SESSION['last_active']) > $inactive) && !isset($_SESSION['loginuser'])) {
        $isValid = false;
        signout();
    }
    else {
        $_SESSION['last_active'] = time();
    }

    return $isValid;
}
?>
