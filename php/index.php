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
    $cont = preg_replace('/^ +/m', '', file_get_contents($txt));
    
    $checks = array();
    if (file_exists($log)) $checks = json_decode(file_get_contents($log), true);
 
    $lines = explode(PHP_EOL, $cont);
    $id = 0;
    $lset = false;
    $lok = false;
    $listing = false;
    foreach ($lines as $line) {
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
                $lset = false;
                $lok = true;
                checkul(true);
                ++$id;
                $cl = isset($checks[$id]) ? ' class=x' : '';
                echo "<li id=$id$cl><a onclick='ck(this)'>$line</a></li>\n";
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
