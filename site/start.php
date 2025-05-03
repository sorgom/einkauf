
<?php
    require_once("view.php");
    require_once("data.php");
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
<div class=itxt><?php
    $iFile = 'start.txt';
    if (!file_exists($iFile)) $iFile = 'start_default.txt';
    echo htmlentities(trim(file_get_contents($iFile)));
?></div>
<textarea class=line readonly autofocus spellcheck=false autofocus onFocus='this.select();this.setSelectionRange(0, 99999);'><?php
echo $_SERVER['REQUEST_SCHEME']; echo '://'; echo $_SERVER['HTTP_HOST']; echo"?$uid";
?></textarea>
<div class='mn bottom'> <a class='enter' href=/?<?php echo $uid; ?>></a></div>
</body></html>
