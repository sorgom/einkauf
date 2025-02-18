<?php 
    require_once('usr.php');
    checkusr();
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
<form action=reset.php method=post>
<input type=submit value="Zurücksetzen" class="bt skip">
<?php usrtag(); ?>
</form>
<form action=input.php method=post>
<input type=submit value="Zur Eingabe" class="bt ok">
<?php usrtag(); ?>
</form>
</body></html>
