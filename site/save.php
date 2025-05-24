<?php
//  save user data from input form
    require_once('data.php');
    usr()->check();
    $data = new Data();
    $data->set($_POST['txt']);
    usr()->view();
?>
