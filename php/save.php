<?php
$file = "data/FF00E1A4.txt";
$fh = fopen($file, "w") or die("Unable to open $file");
fwrite($fh, $_POST['data']);
fclose($fh);
header('Location: /');
exit;
?>