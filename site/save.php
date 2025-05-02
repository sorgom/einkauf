<?php
    require_once('data.php');
    usr()->check();
    $data = new Data();
    $data->load();
    $data->set($_POST['txt']);

    $data->save();
    usr()->view();
?>
