
<?php
    require_once('usr.php');
    usr()->check();
    require_once('body.php');
?>
<script src=view.js></script>
<?php
    $x = usr()->param();
    $uid = usr()->uid();
    $uid = "'$uid'";
    switch ($x)
    {
        case NULL:
            $obj = "Menu($uid)";
            break;
        case 'e':
            $obj = "InputForm($uid)";
            break;
        default:
            $obj = "Items($uid,$x)";
    }
?>
<script>new <?php echo $obj; ?>;</script>
</body></html>
