<?php
    require_once('data.php');
    usr()->check();

    if (empty($_POST['txt'])) usr()->go('input');
    $data = new Data();
    $data->set($_POST['txt']);
    $data->save();
    usr()->view();
?>
