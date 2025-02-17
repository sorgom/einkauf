<?php 
    require_once("usr.php");
    checkusr();
    require_once("head.htm");
    $inul = false;
    function checkul($on)
    {
        global $inul;
        if ($inul == $on) return;
        $inul = $on;
        echo $on ? "<ul>\n" : "</ul>\n";
    }
?>
<body class=paper>
<script>setusr('<?php echo $usr?>');</script>
<div class=handy>
<?php
    if (!file_exists($txt)) go('input.php');
    $cont = file_get_contents($txt);
    $checks = array();
    if (file_exists($log)) $checks = json_decode(file_get_contents($log), true);
 
    $lines = explode("\n", $cont);
    $id = 0;
    $lset = false;
    $lok = false;
    $listing = false;
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/^# *(.*)/', $line, $match)) 
        {
            checkul(false);
            echo "<h2>$match[1]</h2>\n";
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
                // if ($lset) echo "<p> </p>\n";
                $lset = false;
                $lok = true;
                checkul(true);
                ++$id;
                $cl = isset($checks[$id]) ? ' class=x' : '';
                echo "<li id=$id$cl><a onclick='ck(this)'>$line</a></li>\n";
                // echo "<p><input type=checkbox id=$id onclick='ck(this)' $checked> <label for=$id>$line</label></p>\n";
            }
        }
    }
    checkul(false);
?>
</div>
<a class="top reset" href="reset.php?<?php echo $usr?>">Zurücksetzen</a>
<a class=top href="input.php?<?php echo $usr?>">Zur Eingabe</a>
</body></html>
