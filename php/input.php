
<?php 
    session_start();
    require_once('usr.php');
    setusr();
    require_once("head.htm");
    $state = ' disabled';
?>
<body>
<form action=data.php method=post>
    <textarea name=data cols=40 rows=30 autofocus oninput="checkinput(this, 'ok')"><?php
        $cont = '';
        if (isset($_SESSION['data'])) 
        {
            $cont = $_SESSION['data'];
        }
        elseif (file_exists($txt)) 
        {
            $cont = trim(file_get_contents($txt));
        }
        if ($cont) 
        {
            $state = '';
            echo $cont;
            echo "\n\n";
        }
?></textarea>
<p>
<input type=submit value="" name=prev class="bt prev">
<input type=submit value="" name=ok class="bt ok" id=ok <?php echo $state; ?>>
    <input type=submit value="" name=cancel class="bt nok">
</p>
</form>
</body></html>