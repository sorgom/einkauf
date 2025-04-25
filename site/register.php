
<?php
    require_once("view.php");
    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    $reg->add($uid);
    $reg->save();
    usr()->set($uid);
    $txt = file_get_contents('template/template.txt');
    $data = new Data();
    $data->set($txt);
    $data->save();
?>
<div id=info>
<h2>OK</h2>
<p>Kopiere dir diesen Link:</p>
<textarea id=link readonly autofocus spellcheck=false autofocus onFocus='this.select();this.setSelectionRange(0, 99999);'>
<?php echo $_SERVER['REQUEST_SCHEME']; echo '://'; echo $_SERVER['HTTP_HOST']; echo"?$uid"; ?>
</textarea>
<p>und lege los ...</p>
</div>
<?php new UsrStart(); ?>
</body></html>
