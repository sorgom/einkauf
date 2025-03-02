
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
    <textarea name=data rows=20 autofocus oninput="checkInput(this, 'save', 'prev')"><?php
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
<input type=submit value='' name=prev id=prev <?php echo $state; ?>>
<input type=submit value='' id=save  <?php echo $state; ?>>
<input type=submit value='' name=cancel id=cancel>
<input type=hidden name=uid value=<?php echo $uid; ?>>
</form>
</body></html>
