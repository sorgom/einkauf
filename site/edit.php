
<?php
    require_once('view.php');
    require_once('data.php');
    usr()->check();
    $c = json_encode([usr()->uid(), data()->txt()]);
?>
<script src=menu.js></script>
<script>new InputForm(<?php echo $c; ?>);</script>
</body></html>
