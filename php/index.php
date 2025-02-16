<?php 
    require_once("usr.php");
    checkusr();
    require_once("head.htm");
?>
<body>
<script>setusr('<?php echo $usr?>');</script>
<form action=input.php method=post>
<?php usrtag(); ?>
<input type=submit value="Zur Eingabe" class=link>
</form> 
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
                ++$id;
                $checked = isset($checks[$id]) ? ' checked' : '';
                echo "<p><input type=checkbox id=$id onclick='ck(this)' $checked> <label for=$id>$line</label></p>\n";
            }
        }
    }
?>
</div>
</body></html>
