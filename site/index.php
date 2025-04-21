<?php
    require_once('usr.php');
    setUid();
    require_once("body.php");
    require_once('fio.php');

    $chap = getParam();
    rDone($done);
    rData($heads, $items);

    //  no chapter: display menu
    if (is_null($chap))
    {
        echo "<div id=menu>\n";
        foreach ($heads as $p => $head)
        {
            $ok = !empty($items[$p]);
            if ($ok) b_chap($p, $head, isset($done[$p]));
        }
        echo "</div>\n";
        b_edit();
        b_imprint();
    }
    //  chapter number given: display chapter
    else
    {
        b_top($heads[$chap], $chap);
        echo "<div id=items>\n";
        $inr = 0;
        foreach ($items[$chap] as $i)
        {
            if (empty($i)) echo "<hr>\n";
            elseif (is_array($i));
            elseif ($i[0] == '#')
            {
                echo '<h3>' . substr($i, 1) . "</h3>\n";
            }
            else
            {
                b_item($chap, $inr, $i);
                ++$inr;
            }
        }
        echo "</div>\n";
        echo "<script src=view.js></script>\n";
        echo "<script>setUid('$uid');</script>\n";
        b_reset($chap);
        b_remove($chap);
    }
?>
</body></html>
