
<?php 
    require_once("head.htm");
    require_once("usr.php");
    $reg = getreg();
    $usr = '';
    do {
        $usr = strtoupper(dechex(rand(0xF0000000, 0xFFFFFFFF)));
    } while (array_key_exists($usr, $reg));
?>
<body>
<h3>Hallo</h3>
<p>Kopiere dir diesen Link:</p>
<p id=url class=copy>http://<?php echo $_SERVER['HTTP_HOST']; echo "?$usr"?></p><br>
<input type=submit value=Kopieren class="bt ok" onclick="cc('url');enable('ok')">
<form action=reg.php method=post>
    <?php usrtag(); ?>
    <input type=submit value=Registrieren name=ok class="bt ok" id=ok disabled>
</form>
</body></html>
