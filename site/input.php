
<?php
    require_once('TxtObject.php');
    usr()->check();
    require_once("body.php");
    require_once("OutputObjects.php");
?>
<script src=input.js></script>
<form action=save.php method=post id=ff>
    <textarea name=txt rows=20 autofocus oninput="checkInput(this, 'save')"><?php
    echo Txt::instance()->txt();
?></textarea>
<input type=hidden name=uid value=<?php echo usr()->uid(); ?>>
</form>
<div id=navi>
<?php new Back(); ?>
<a onclick="document.getElementById('ff').submit();" id=save> </a>
</div>
</body></html>
