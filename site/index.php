<?php
    require_once("body.php");
    require_once('OutputObjects.php');
    usr()->check();

    $chap = usr()->param();
    //  no chapter: display menu
    if (is_null($chap))
    {
        new Menu();
    }
    //  chapter number given: display chapter
    else
    {
        new ItemList($chap);
        echo "<script src=view.js></script>\n";
        echo "<script>setUid('" . usr()->uid() . "');</script>\n";
    }
?>
</body></html>
