<?php
require_once('usr.php');
require_once('head.htm');
setusr();
require_once('buttons.php');
if (session_status() == PHP_SESSION_ACTIVE)
{
    session_destroy();
}
?>
<body>
<?php
    b_login();
?>
</body></html>
