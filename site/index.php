
<?php
    require_once('data.php');
    usr()->check();
    require_once('body.php');
?>
<script src=view.js></script>
<?php
    $x = usr()->param();
    $data = NULL;
    switch ($x)
    {
        case NULL:
            data()->menuData($data);
            $obj = 'Menu';
            break;
        case 'e':
            data()->txt($data);
            $obj = 'InputForm';
            break;
        default:
            data()->ItemData($data, $x);
            $obj = 'Items';
    }
    $uid = usr()->uid();
?>
<script>new <?php echo "$obj('$uid', "; echo json_encode($data); echo ')' ?>;</script>
</body></html>
