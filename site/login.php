<?php
require_once('usr.php');
if (!usr()->isValid()) Usr::welcome();
if ($_POST)
{
    if (usr()->isEncrypted())
    {
        if (!(
            isset($_POST['uid']) &&
            isset($_POST['pwd'])
        )) Usr::welcome();

        $pwd = $_POST['pwd'];
        usr()->checkPwd($pwd);
        session_start();
        $_SESSION['uid'] = $_POST['uid'];
        $_SESSION['key'] = fnc\key($pwd1);
    }
    usr()->view();
}
else
{
    require_once('view.php');
    $data = [];
    jsView('LoginForm', usr()->uid(), $data);

}
?>
