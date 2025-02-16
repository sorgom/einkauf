
<?php 
    require_once("head.htm");
    require_once("usr.php");
    $reg = getreg();
    $usr = '';
    do {
        $usr = strtoupper(dechex(rand(0xF0000000, 0xFFFFFFFF)));
    } while (isset($reg[$usr]));
?>
<body>
<h3>Hallo</h3>
<p>Kopiere dir diesen Link:</p>
<input type=text value="http://<?php echo $_SERVER['HTTP_HOST']; echo "?$usr"?>" 
size=50 readonly autofocus onFocus="this.select();this.setSelectionRange(0, 99999);"><br>
<form action=input.php method=post>
    <?php usrtag(); ?>
    <input type=hidden name=isnew value=1>
    <input type=submit value=OK name=ok class="bt ok" id=ok>

</form>
</body></html>
