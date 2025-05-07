
<?php
    require_once('body.php');
    require_once('data.php');
    require_once('dtxt.php');

    $addr = $_GET['em'];

    echo "email: $addr\n";

    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    $reg->add($uid);
    $reg->save();

    $srv = $_SERVER['SERVER_NAME'];
    $req = $_SERVER['HTTP_HOST'];
    $prt = $_SERVER['REQUEST_SCHEME'];

    echo "$srv\n";

    $link = "$prt://$req?$uid";

    $subject = "Dein Link";
    $message = $link;
    $header = array(
        'From' => "Wellcome <welcome@$srv>",
        'Reply-To' => "no-reply@$srv",
        'X-Mailer' => 'PHP/' . phpversion()
    );

    $success = mail($addr, $subject, $message, $header);
    var_dump($success);
    // $reg = reg();
    // do {
    //     $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    // } while ($reg->has($uid));
    // $reg->add($uid);
    // $reg->save();
    // usr()->set($uid);
    // $txt = dtxt('template');
    // $data = new Data();
    // $data->set($txt);
?>
<div class=itxt>
Den Link haben wir an <?php echo $addr; ?> gesendet.

Hier kannst du schon loslegen.
</div>

<a class='i enter' href=<?php echo $link; ?>></a>
