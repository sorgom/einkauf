
<?php 
    require_once("head.htm");
    require_once("usr.php");
    $reg = getreg();
    getusr();
    $reg[$usr] = 1;
    file_put_contents(regf(), json_encode($reg));
?>
<body>
<h3>Hallo</h3>
<p>Du bist mit diesem Link registriert:</p>
<p id=url class=copy>http://<?php echo $_SERVER['HTTP_HOST']; echo "?$usr"?></p><br>
<input type=submit value=Kopieren class="bt ok" onclick="cc('url')">
<form action=input.php method=post>
    <?php usrtag(); ?>
    <input type=submit value="Zur Eingabe" class="bt ok">
</form>
</body></html>
