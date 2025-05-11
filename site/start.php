
<?php
    require_once('body.php');
    require_once('usr.php');
    // require_once('texter.php');

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

    $subject = $srv;
    $header = array(
        'From' => "Wellcome <welcome@$srv>",
        'Reply-To' => "no-reply@$srv",
        'X-Mailer' => 'PHP/' . phpversion()
    );

    function ignore_errors(... $params) {}
    set_error_handler('ignore_errors');

    $success = false;
    $success = @mail($addr, $subject, $link, $header);

    $data = [$success, $addr, $link];
    jsNew('StartInfo', $uid, $data);
?>
<div class='grow_up itxt'>
    <?php if ($success) { ?>
        <div class='ico ok'><?php echo $addr; ?></div>
    <?php } else { ?>
        <div class='ico nok'><?php echo $addr; ?></div>
    <?php } ?>
    <div class='ico go'><a href=<?php echo $link; ?> class=keep><?php echo $link; ?></a></div>
</div>
