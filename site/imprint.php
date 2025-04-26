<?php
    require_once("view.php");
    // new ImprintBack();
?>
<div id=navi> <?php new ImprintBack(); ?></div>
<div class=imprint>
<?php
    $iFile = 'imprint.txt';
    if (file_exists($iFile))
    {
        echo htmlentities(file_get_contents($iFile));
    }
    else
    {
        echo "Imprint not found.";
    }

?>


This is Open Source.
<a href='https://github.com/sorgom/todo' target=_blank>view on github ..</a>

PHP <?php echo phpversion() ?>

</div><div id=bottom> <?php new Back(); ?></div>
</body></html>
