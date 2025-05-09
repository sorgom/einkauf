<?php
require_once('body.php');
require_once('texter.php');
$texter = texter();
$texter->get($w1, 'welcome 1');
$texter->get($w2, 'welcome 2');
$texter->get($em, 'email');
?>
<div class=grow_up>
<div class=itxt><?php echo $w1;?></div>
<form action=start.php method=POST>
    <div class=center><input type=email name=em id=em class=em autofocus required placeholder='<?php echo $em; ?>'></div>
    <div class=center><input type=submit class='i enter' value=''></div>
</form>
<div class=itxt><?php echo $w2; ?></div>
</div>
