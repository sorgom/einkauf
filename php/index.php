<?php
    session_start();
    require_once('usr.php');
    setusr();
    checkusr();
    require_once("head.htm");
    $prev = isset($_SESSION['data']);
    $chap = $prev ? Null : getparam();
?>
<body>
<script>setusr('<?php echo $usr?>');</script>
<?php
    require_once('fio.php');
    require_once('buttons.php');

    if ($chap) b_top();
    if ($chap || $prev)
    {
        echo '<div class=\'display';
        if ($prev) echo ' prev';
        echo "'>\n";
    }

    $inul = false;
    function checkul($on)
    {
        global $inul;
        if ($inul == $on) return;
        $inul = $on;
        echo $on ? "<ul>\n" : "</ul>\n";
    }
    $lines = $prev ? tolines($_SESSION['data']) : getlines();
    $done = $prev ? array() : getdone();
    $cnr = 0;
    $inr = 0;
    $lset = false;
    $lok = false;
    $listing = false;
    foreach ($lines as $line) {
        if ($lvl = totop($line, $top, $cnr, $inr))
        {
            if ($lvl == 1)
            {
                if ($prev) $listing = true;
                elseif ($chap)
                {
                    $listing = $cnr == $chap;
                }
                else
                {
                    b_chap($cnr, $top, isset($done[$cnr]));
                }
            }
            if ($listing)
            {
                checkul(false);
                echo "<h$lvl>$top</h$lvl>\n";
                $lset = false;
                $lok = false;
            }
        }
        else if($listing)
        {
            if (empty($line)) $lset = $lok;
            else
            {
                if ($lset) echo "<hr/>\n";
                $lset = false;
                $lok = true;
                checkul(true);
                if ($prev)
                {
                    echo "<li><a>$line</a></li>\n";
                }
                else
                {
                    ++$inr;
                    $cl = isset($done["$cnr.$inr"]) ? ' class=x' : '';
                    echo "<li id=$cnr.$inr$cl><a onclick='ck(this)'>$line</a></li>\n";
                }
            }
        }
    }
    checkul(false);
    if ($chap || $prev)
    {
        echo "</div>\n";
    }
?>
<?php
    if ($prev)
    {
        b_prev_write();
        b_edit();
        b_prev_cancel();
    }
    elseif ($chap)
    {
        bt_chap_done($chap);
        b_reset($chap);
    }
    else {
        b_edit();
        b_logout();
    }
?>
</body></html>
