
<?php
    require_once("view.php");
    usr()->check();
    $cnr = usr()->param();
    if (is_null($cnr)) usr()->view();
?>
<div id=navi>
<?php new Back(); ?>
<a onclick="document.getElementById('ff').submit();" id=save> </a>
</div>
<form action=save.php method=post id=ff>
    <textarea name=ttl rows=2><?php echo data()->head($cnr); ?></textarea>
    <textarea name=txt rows=20 autofocus class=txt><?php
    echo data()->ctxt($cnr);
?></textarea>
<input type=hidden name=uid value=<?php echo usr()->uid(); ?>>
<input type=hidden name=cnr value=<?php echo $cnr; ?>>
</form>
</body></html>
