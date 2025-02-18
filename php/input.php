
<?php 
    session_start();
    require_once('usr.php');
    getusr();
    require_once("head.htm");
    $state = ' disabled';
    print_r($usr)
?>
<body>
<h3>hier reinkopieren</h3>
<form action=save.php method=post>
    <textarea name=data cols=40 rows=30 autofocus oninput="checkinput(this, 'ok')"><?php
        if (file_exists($txt)) 
        {
            $cont = trim(file_get_contents($txt));
            if ($cont) 
            {
                $state = '';
                echo $cont;
                echo "\n\n";
            }
        }
    ?></textarea>
    <p><input type=submit value=OK name=ok class="bt ok" id=ok <?php echo $state; ?>>
    <input type=submit value=Abbruch name=skip class="bt nok"></p>
    <?php usrtag(); ?>
</form>
</body></html>