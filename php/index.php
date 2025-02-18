<?php 
    session_start();
    require_once('usr.php');
    $usr = '';
    if ($_GET)
    {
        $x = array_keys($_GET)[0];
        if (isusr($x)) 
        {
            $usr = $x;
            $_SESSION['usr'] = $usr;
        }
    }
    elseif (isset($_SESSION['usr']))
    {
        $usr = $_SESSION['usr'];
    }
    if (!$usr) go('new.php');
    setusr();
    require_once("head.htm");
?>
<body>
<script>setusr('<?php echo $usr?>');</script>
<div class=handy>
<?php
    if (!file_exists($txt)) go('input.php');
    require_once('fio.php');

    $inul = false;
    function checkul($on)
    {
        global $inul;
        if ($inul == $on) return;
        $inul = $on;
        echo $on ? "<ul>\n" : "</ul>\n";
    }

    $lines = getlines();
    $checks = getchecks();
    $inr = 0;
    $cnr = 0;
    $lset = false;
    $lok = false;
    $listing = false;
    foreach ($lines as $line) {
        if ($lvl = totop($line, $top, $cnr)) 
        {
            checkul(false);
            echo "<h$lvl>$top</h$lvl>\n";
            $listing = true;
            $lset = false;
            $lok = false;
        } 
        else if ($listing)
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
?>
</div>
<hr/>
<a href=reset.php class="bt res">Zurücksetzen</a>
<a href=input.php class="bt ok">Zur Eingabe</a>
</body></html>
