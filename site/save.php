<?php
    require_once('data.php');
    usr()->check();
    $cnr = NULL;
    $data = new Data();
    if (isset($_POST['cnr']))
    {
        $cnr = $_POST['cnr'];
        $data->load();
        $data->setc($cnr, $_POST['ttl'], $_POST['txt']);
    }
    else $data->set($_POST['txt']);

    $data->save();
    usr()->view($cnr);
?>
