
<?php
    require_once("view.php");
    usr()->check();
?>
<form action=save.php method=post id=ff>
    <textarea name=txt rows=20 autofocus><?php
    echo data()->txt();
?></textarea>
<input type=hidden name=uid value=<?php echo usr()->uid(); ?>>
</form>
<div id=navi>
<?php new Back(); ?>
<a onclick="document.getElementById('ff').submit();" id=save> </a>
</div>
</body></html>
