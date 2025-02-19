
<?php 
    require_once("head.htm");
    require_once('usr.php');
    require_once('buttons.php');
    $reg = getreg();
    $nu = '';
    do {
        $nu = strtoupper(dechex(rand(0xF0000000, 0xFFFFFFFF)));
    } while (isset($reg[$nu]));
    $reg[$nu] = 1;
    file_put_contents(regf(), json_encode($reg));
?>
<body>
<h1>Kopiere dir diesen Link:</h1>
<input type=text value="http://<?php echo $_SERVER['HTTP_HOST']; echo "?$nu"?>" 
size=50 readonly autofocus onFocus="this.select();this.setSelectionRange(0, 99999);"/><br/>
<?php 
    b_new_go($nu);
?>
</body></html>
