<?php
    require_once('data.php');
    usr()->check();

    $data = new Data();
    $data->set($_POST['txt']);
    $data->save();
    usr()->view();
?>
