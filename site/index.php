
<?php
    require_once('data.php');
    usr()->check();
    require_once('_view.php');
?>
<script src="_view.js"></script>
<?php
    $cnr = usr()->param();
    if (!is_null($cnr))
    {
        $data = [
            usr()->uid(), $cnr, data()->heads()[$cnr],
            data()->lines($cnr), states()->items($cnr)
        ];
        $script = 'items.js';
        $call = 'gen_items';
    }
    else
    {
        $data = [
            usr()->uid(), data()->heads(),
            states()->chapters(), data()->ecs()];
            $script = 'menu.js';
            $call = 'gen_menu';
    }
    $c = json_encode($data);
    echo "<script src=$script></script>\n";
    echo "<script>gen($c);</script>\n";
?>
</body></html>
