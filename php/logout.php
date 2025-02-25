<?php
require_once('usr.php');
require_once("body.php");
setUid();
if (session_status() == PHP_SESSION_ACTIVE) session_destroy();
b_login();
?>
</body></html>
