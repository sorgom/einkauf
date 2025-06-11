
<?php
    //  evaluation of new user welcome form
    require_once('usr.php');

    //  check if called from appropriate form
    if (!(
        isset($_POST['em']) &&
        isset($_POST['pwd1']) &&
        isset($_POST['pwd2'])
    )) Usr::welcome();

    //  if password given both inputs must match
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];
    if (($pwd1 || $pwd2) && ($pwd1 != $pwd2)) Usr::welcome();

    $mail = $_POST['em'];

    //  generate random ids until not yet registered
    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    //  register new id
    $reg->add($uid, $pwd1);
    $reg->save();

    //  if user has encryption: start session with encryption / decryption key
    if ($pwd1)
    {
        session_start();
        $_SESSION['uid'] = $uid;
        $_SESSION['key'] = fnc\key($pwd1);
    }

    $srv = $_SERVER['SERVER_NAME'];
    $req = $_SERVER['HTTP_HOST'];
    $prt = fnc\protocol();

    //  the link
    $link = "$prt://$req?$uid";

    //  if email provided: try send
    if ($mail)
    {
        $subject = $srv;
        $header = array(
            'From' => "sign-up@$srv",
            'Reply-To' => "no-reply@$srv",
            'X-Mailer' => 'PHP/' . phpversion()
        );

        function ignore_errors(... $params) {}
        set_error_handler('ignore_errors');

        @mail($mail, $subject, $link, $header);
    }
    //  start view
    // $data = [$ok, $mail, $link];
    require_once('view.php');
    jsView('StartInfo', $uid, $link);
?>
