<?php
    require_once("view.php");
?>
<div id=imprint>
<?php
    $iFile = 'imprint.txt';
    if (!file_exists($iFile)) $file = 'imprint_default.txt';
    echo htmlentities(file_get_contents($iFile));
?>

This is Open Source.
<a href='https://github.com/sorgom/todo' target=_blank>view on github ..</a>

PHP <?php echo phpversion() ?>
</div>
<!-- <div class='mn bottom'> <a class='back' onclick='history.back();'> </a></div> -->
</body></html>
