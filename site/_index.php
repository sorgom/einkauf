
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
            data()->citems($cnr), states()->items($cnr) ];
        $call = 'gen_items';
    }
    else
    {
        $nd = [];
        foreach(data()->items() as $cnr => $i)
        {
            if (empty($i)) $nd[] = $cnr;
        }
        $data = [
            usr()->uid(), data()->heads(),
            states()->chapters(), $nd ];
        $call = 'gen_menu';
    }
    $c = json_encode($data);
    echo "<script>$call($c);</script>\n";
?>
</body></html>
