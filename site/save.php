<?php
    require_once('DataObjects.php');
    Usr::instance()->check();

    if (empty($_POST['txt'])) Usr::instance()->go('input');

    $txt = new Txt();
    $txt->set($_POST['txt']);
    $txt->save();
    Usr::instance()->view();
?>
