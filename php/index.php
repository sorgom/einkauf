<?php
    session_start();
    require_once('usr.php');
    setUid();
    checkUid();
    require_once("body.php");
    require_once('fio.php');

    $prev = isset($_SESSION['data']);
    $chap = $prev ? Null : getParam();
    $inr = 0;
    $cnr = 0;
    $done = array();
    if (!$prev) rDone($done);

    function chapItem($item)
    {
        global $done, $cnr, $inr;
        $id = "$cnr.$inr";
        $cl = isset($done[$id]) ? ' class=x' : '';
        echo "<a id=$id$cl onclick='ck(this)'>$item</a>\n";
    }

    function prevItem($item)
    {
        echo "<a>$item</a>\n";
    }

    function dispItem($item, $iFunc)
    {
        global $inr;
        $inr = 0;

        foreach ($item as $i)
        {
            if (empty($i)) echo "<hr/>\n";
            elseif (is_array($i))
            {
                echo "<h$i[0]>$i[1]</h$i[0]>\n";
            }
            else
            {
                ++$inr;
                $iFunc($i);
            }
        }
    }
    //  chapter number given: display chapter
    if ($chap)
    {
        rData($heads, $items);
        $cnr = $chap;
        $pos = $chap - 1;
        b_top($heads[$pos], isset($done[$chap]));
?>
<script src=view.js></script>
<script>setUid('<?php echo $uid?>');</script>
<div id=items class=chap><?php
        dispItem($items[$pos], 'chapItem');
    }
    //  preview: display all chapters
    elseif ($prev)
    {
?><div id=items class=prev><?php
       txt2data($_SESSION['data'], $heads, $items);
       $pos = 0;
       foreach ($heads as $h)
       {
           echo "<h1>$h</h1>\n";
           dispItem($items[$pos], 'prevItem');
           ++$pos;
       }
    }
    //  otherwise: display Menu
    else
    {
        rData($heads, $items);
        $cnr = 0;
        foreach ($heads as $head)
        {
            ++$cnr;
            b_chap($cnr, $head, isset($done[$cnr]));
        }
    }

    if ($chap || $prev)
    {
        echo "</div>\n";
    }

    if ($prev)
    {
        b_prev_write();
        b_edit();
        b_prev_cancel();
    }
    elseif ($chap)
    {
        // bt_chap_done($chap);
        b_remove($chap);
        b_reset($chap);
    }
    else {
        b_edit();
        b_logout();
    }
?>
</body></html>
