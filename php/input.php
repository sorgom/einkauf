
<?php 
    require_once("usr.php");
    getusr();
    require_once("head.htm");
?>
<body>
<h3>hier reinkopieren</h3>
<form action=save.php method=post>
    <textarea name=data cols=100 rows=30 autofocus></textarea>
    <p><input type=submit value=OK name=ok class="bt ok">
    <input type=submit value=cancel name=skip class="bt skip"></p>
    <?php usrtag(); ?>
</form>
</body></html>