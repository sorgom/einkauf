
<?php
    require_once('usr.php');
    setUid();
    require_once("body.php");
    $state = ' disabled';
    require_once('fio.php');
?>
<script src=input.js></script>
<form action=data.php method=post>
    <textarea name=txt rows=20 autofocus oninput="checkInput(this, 'save')"><?php
    rTxt($txt);
    if ($txt)
    {
        echo $txt;
        $state = '';
    }
?></textarea>
<input type=submit value='' id=save  <?php echo $state; ?>>
<input type=submit value='' name=cancel id=cancel>
<input type=hidden name=uid value=<?php echo $uid; ?>>
</form>
</body></html>
