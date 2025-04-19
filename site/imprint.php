<?php
    require_once("body.php");
    b_imprint_back();
?>

<div id=info>
<?php
    $iFile = 'imprint.txt';
    if (file_exists($iFile))
    {
        $imprint = file_get_contents($iFile);
        echo "<pre>$imprint<a href='https://github.com/sorgom/todo' target=_blank>This is Open Source.</a></pre>";
    }
    else
    {
        echo "<p>Imprint not found.</p>";
    }
?>
</div>
<?php b_back(); ?>
</body></html>
