
<?php
    require_once('usr.php');

    if (!(
        isset($_POST['em']) &&
        isset($_POST['pwd1']) &&
        isset($_POST['pwd2'])
    )) Usr::welcome();

    $addr = $_POST['em'];
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];
    if (($pwd1 || $pwd2) && ($pwd1 != $pwd2)) Usr::welcome();

    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    $reg->add($uid, $pwd1);
    $reg->save();

    if ($pwd1)
    {
        session_start();
        $_SESSION['uid'] = $uid;
        $_SESSION['key'] = fnc\key($pwd1);
    }

    $srv = $_SERVER['SERVER_NAME'];
    $req = $_SERVER['HTTP_HOST'];
    $prt = $_SERVER['REQUEST_SCHEME'];

    $link = "$prt://$req?$uid";
    $ok = false;

    if ($addr)
    {
        $subject = $srv;
        $header = array(
            'From' => "Wellcome <welcome@$srv>",
            'Reply-To' => "no-reply@$srv",
            'X-Mailer' => 'PHP/' . phpversion()
        );

        function ignore_errors(... $params) {}
        set_error_handler('ignore_errors');

        $ok = @mail($addr, $subject, $link, $header);
    }
    $data = [$ok, $addr, $link];
    require_once('view.php');
    jsView('StartInfo', $uid, $data);
?>
