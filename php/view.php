<?php 
    session_start();
    require_once('usr.php');
    setusr();
    require_once("head.htm");
    $prev = isset($_SESSION['data']);
    $chap = $prev ? false : sval('chap');
?>
<body <?php echo $prev ? 'class=prev' : ''?>>
<script>setusr('<?php echo $usr?>');</script>
<?php
    require_once('fio.php');
    require_once('buttons.php');

    if ($chap) b_top();

    $inul = false;
    function checkul($on)
    {
        global $inul;
        if ($inul == $on) return;
        $inul = $on;
        echo $on ? "<ul>\n" : "</ul>\n";
    }
    $indiv = false;
    function checkdiv($on)
    {
        global $indiv;
        if ($indiv == $on) return;
        $indiv = $on;
        echo $on ? "<div class=cont>\n" : "</div>\n";
    }

    $lines = $prev ? tolines($_SESSION['data']) : getlines();
    $checks = $prev ? array() : getchecks();
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
                    b_chap($cnr, $top);
                }
                if ($listing)
                {
                    checkdiv(false);
                    checkdiv(true);
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
                ++$inr;
                $cl = isset($checks["$cnr.$inr"]) ? ' class=x' : '';
                echo "<li id=$cnr.$inr$cl><a onclick='ck(this)'>$line</a></li>\n";
            }
        }
    }
    checkul(false);
    checkdiv(false);
?>
<?php
    if ($prev)
    {
        b_prev_write();
        b_input();
        b_prev_cancel();
    }
    else
    {
        if ($chap) b_reset($chap);
        b_input();
    }
?>
</body></html>
