<?php
session_start();
session_unset();
session_destroy();
if (isset($_COOKIE['lembrar_email'])) {
    setcookie('lembrar_email', '', time() - 3600, '/', '', false, true);
}
header("Location: login.html");
exit;
?>
