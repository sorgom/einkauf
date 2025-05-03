
<?php
    require_once("view.php");
?>
<div class=itxt><?php
    $iFile = 'hello.txt';
    if (!file_exists($iFile)) $iFile = 'hello_default.txt';
    echo htmlentities(trim(file_get_contents($iFile)));
?>


This is Open Source.
<a href='https://github.com/sorgom/todo' target=_blank>view on github ..</a>
[ PHP <?php echo phpversion() ?> ]</div>
<div class='mn bottom'> <a class='enter' href=start.php> </a></div>
</body></html>
