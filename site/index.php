
<?php
    require_once('data.php');
    usr()->check();
    require_once('view.php');
?>
<?php
    $cnr = usr()->param();
    $script = 'menu.js';
    if (is_null($cnr))
    {
        $data = data()->menuData($cnr);
        $class = 'Menu';
    }
    else
    {
        $data = data()->ItemData($cnr);
        $class = 'Items';
    }
    $c = json_encode($data);
    echo "<script src=$script></script>\n";
    echo "<script>new $class($c);</script>\n";
?>
</body></html>
