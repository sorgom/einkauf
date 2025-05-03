
<?php
    require_once('view.php');
    require_once('usr.php');
    usr()->check();
?>
<script src=menu.js></script>
<script>new InputForm('<?php echo usr()->uid(); ?>');</script>
</body></html>
