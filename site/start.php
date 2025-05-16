
<?php
    require_once('view.php');
    require_once('usr.php');

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

    $srv = $_SERVER['SERVER_NAME'];
    $req = $_SERVER['HTTP_HOST'];
    $prt = $_SERVER['REQUEST_SCHEME'];


    $link = "$prt://$req?$uid";

    $subject = $srv;
    $header = array(
        'From' => "Wellcome <welcome@$srv>",
        'Reply-To' => "no-reply@$srv",
        'X-Mailer' => 'PHP/' . phpversion()
    );

    function ignore_errors(... $params) {}
    set_error_handler('ignore_errors');

    $ok = false;
    $ok = @mail($addr, $subject, $link, $header);

    $data = [$ok, $addr, $link];
    jsView('StartInfo', $uid, $data);
?>
