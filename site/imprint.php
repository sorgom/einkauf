<?php
    require_once("view.php");
    new ImprintBack();
?>

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

</div>
<?php new Back(); ?>
</body></html>
