
<?php
    require_once('body.php');
    require_once('usr.php');
    require_once('texter.php');

    $addr = $_POST['em'];

    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    $reg->add($uid);
    $reg->save();

    $srv = $_SERVER['SERVER_NAME'];
    $req = $_SERVER['HTTP_HOST'];
    $prt = $_SERVER['REQUEST_SCHEME'];


    $link = "$prt://$req?$uid";

    texter()->get($subject, 'mail subject');

    $message = $link;
    $header = array(
        'From' => "Wellcome <welcome@$srv>",
        'Reply-To' => "no-reply@$srv",
        'X-Mailer' => 'PHP/' . phpversion()
    );

    function ignore_errors(... $params) {}
    set_error_handler('ignore_errors');

    $success = false;
    $success = @mail($addr, $subject, $message, $header);

    texter()->get($txt, $success ? 'mail sent' : 'mail failed');
    $txt = str_replace('##MAIL', $addr, str_replace('##LINK', $link, $txt));
?>
<div class=grow_up>
<div class=itxt><?php echo $txt; ?></div>
</div>
