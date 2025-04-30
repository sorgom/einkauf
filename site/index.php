
<?php
    require_once('data.php');
    usr()->check();
    require_once('_view.php');
?>
<?php
    $cnr = usr()->param();
    if (!is_null($cnr))
    {
        $data = [
            usr()->uid(), $cnr, data()->heads()[$cnr],
            data()->lines($cnr), states()->items($cnr)
        ];
        $script = 'items.js';
    }
    else
    {
        $data = [
            usr()->uid(), data()->heads(),
            states()->chapters(), data()->ecs()];
            $script = 'menu.js';
    }
    $c = json_encode($data);
    echo "<script src=$script></script>\n";
    echo "<script>gen($c);</script>\n";
?>
</body></html>
