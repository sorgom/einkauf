
<?php
    require_once("view.php");
    require_once('usr.php');
    require_once('data.php');
    $reg = getReg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while (isset($reg[$uid]));
    $reg[$uid] = 1;
    file_put_contents(regFile(), json_encode($reg));
    $txt = file_get_contents('template/template.txt');
    clean($txt);
    txt2data($txt, $heads, $items);
    wData($heads, $items);
    wTxt($txt);
?>
<div id=info>
<h2>OK</h2>
<p>Kopiere dir diesen Link:</p>
<textarea id=link readonly autofocus spellcheck=false autofocus onFocus='this.select();this.setSelectionRange(0, 99999);'>
<?php echo $_SERVER['REQUEST_SCHEME']; echo '://'; echo $_SERVER['HTTP_HOST']; echo"?$uid"; ?>
</textarea>
<p>und lege los ...</p>
</div>
<?php b_reg_go(); ?>
</body></html>
