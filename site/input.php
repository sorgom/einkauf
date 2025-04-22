
<?php
    require_once('usr.php');
    setUid();
    require_once("body.php");
    require_once('data.php');
?>
<script src=input.js></script>
<form action=save.php method=post id=ff>
    <textarea name=txt rows=20 autofocus oninput="checkInput(this, 'save')"><?php
    rTxt($txt);
    if ($txt)
    {
        echo $txt;
        $state = '';
    }
?></textarea>
<input type=hidden name=uid value=<?php echo $uid; ?>>
</form>
<div id=navi>
<?php b_back(); ?>
<a onclick="document.getElementById('ff').submit();" id=save> </a>
</div>
</body></html>
