<?php
    require_once('TxtObject.php');
    usr()->check();

    if (empty($_POST['txt'])) usr()->go('input');

    $txt = new Txt();
    $txt->set($_POST['txt']);
    $txt->save();
    usr()->view();
?>
