
<?php
    require_once("body.php");
    require_once('usr.php');
    require_once('fio.php');
    $reg = getReg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while (isset($reg[$uid]));
    $reg[$uid] = 1;
    file_put_contents(regFile(), json_encode($reg));
    copy('template/template.txt', "data/$uid.txt");
    copy('template/template.data.json', "data/$uid.data.json");
?>
<div id=info>
<h2>OK</h2>
<p>Kopiere dir diesen Link:</p>
<textarea id=link readonly autofocus spellcheck=false autofocus onFocus='this.select();this.setSelectionRange(0, 99999);'>
<?php echo $_SERVER['REQUEST_SCHEME']; echo $_SERVER['HTTP_HOST']; echo"?$uid"; ?>
</textarea>
<p>und lege los ...</p>
</div>
<?php
    b_reg_go();
?>
</body></html>
