<?php
    require_once("view.php");
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
        echo "<script src=items.js></script>\n";
        echo "<script>setUid('" . usr()->uid() . "');</script>\n";
    }
?>
</body></html>
