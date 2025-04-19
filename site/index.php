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
        foreach ($heads as $p => $head)
        {
            $ok = !empty($items[$p]);
            if ($ok) b_chap($p, $head, isset($done[$p]));
        }
        b_edit();
        b_imprint();
    }
    //  chapter number given: display chapter
    else
    {
        b_top($heads[$chap], isset($done[$chap]));
        echo "<div id=items class=chap>\n";
        $inr = 0;
        foreach ($items[$chap] as $i)
        {
            if (empty($i)) echo "<hr>\n";
            elseif (is_array($i))
            {
                echo "<h$i[0]>$i[1]</h$i[0]>\n";
            }
            else
            {
                $id = "$chap.$inr";
                $cl = '';
                if (isset($done[$id]))
                {
                    $v = $done[$id];
                    $v = $v == '1' ? 'x' : $v;
                    $cl =  " class=$v";
                }
                echo "<div id=$id$cl><a>$i</a></div>\n";
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
