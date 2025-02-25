
<?php
    session_start();
    require_once('usr.php');
    setUid();
    checkUid();
    require_once("body.php");
    $state = ' disabled';
?>
<script src=input.js></script>
<form action=data.php method=post>
    <textarea name=data rows=30 autofocus oninput="checkInput(this, 'ok', 'prev')"><?php
    $cont = NULL;
    if (isset($_SESSION['data'])) $cont = &$_SESSION['data'];
    else {
        require_once('fio.php');
        rTxt($txt);
        $cont = &$txt;
    }
    if ($cont)
    {
        echo $cont;
        $state = '';
    }
?></textarea>
<input type=submit value="" name=prev class="bt prev" id=prev <?php echo $state; ?>>
<input type=submit value="" name=ok class="bt save" id=ok <?php echo $state; ?>>
<input type=submit value="" name=cancel class="bt cancel">
<input type=hidden name=uid value=<?php echo $uid; ?>>
</form>
</body></html>
