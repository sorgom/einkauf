
<?php
    require_once('body.php');
    require_once('data.php');
    require_once('dtxt.php');
    $reg = reg();
    do {
        $uid = strtoupper(dechex(rand(0xA0000000, 0xFFFFFFFF)));
    } while ($reg->has($uid));
    $reg->add($uid);
    $reg->save();
    usr()->set($uid);
    $txt = dtxt('template');
    $data = new Data();
    $data->set($txt);
    $data->save();
?>
<div class=itxt><?php
    echo htmlentities(dtxt('start'));
?></div>
<textarea class=line readonly autofocus spellcheck=false autofocus onFocus='this.select();this.setSelectionRange(0, 99999);'><?php
echo $_SERVER['REQUEST_SCHEME']; echo '://'; echo $_SERVER['HTTP_HOST']; echo "?$uid";
?></textarea>
<div class='mn bottom'> <a class='enter' href=/?<?php echo $uid; ?>></a></div>
</body></html>
