<?php
//  user login: form or input evaluation
require_once('usr.php');
if (!usr()->isValid()) Usr::welcome();
fnc\clearSession();
//  data from form
if ($_POST)
{
    //  if user has encryption: evaluate data
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
        $_SESSION['key'] = fnc\key($pwd);
    }
    usr()->view();
}
//  no POST data: display form
else
{
    require_once('view.php');
    $data = [];
    jsView('LoginForm', usr()->uid(), $data);
}
?>
