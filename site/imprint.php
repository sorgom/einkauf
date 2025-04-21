<?php
    require_once("body.php");
    b_imprint_back();
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
</div>
<?php b_back(); ?>
</body></html>
