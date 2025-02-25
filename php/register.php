
<?php
    require_once("body.php");
    require_once('usr.php');
    $reg = getReg();
    do {
        $uid = strtoupper(dechex(rand(0xF0000000, 0xFFFFFFFF)));
    } while (isset($reg[$uid]));
    $reg[$uid] = 1;
    file_put_contents(regFile(), json_encode($reg));
?>
<h1>Kopiere dir diesen Link:</h1>
<input type=text value="http://<?php echo $_SERVER['HTTP_HOST']; echo "?$uid"?>"
size=50 readonly autofocus onFocus="this.select();this.setSelectionRange(0, 99999);"/><br/>
<?php
    b_new_go();
?>
</body></html>
