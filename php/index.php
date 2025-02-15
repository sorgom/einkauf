<!DOCTYPE html>
<html lang=en>
<head>
<title>einkauf</title>
<meta charset="UTF-8">
<style>
* { font-family:Arial, sans-serif; }
p { margin-left: 1em; vertical-align: top; }
input[type=checkbox] { width: 2em; height: 2em; display: inline-block; }
input[type=checkbox]:checked { background-color: #0F0; }
</style>
</head>
<body>
<H3><a href=input.php>Eingabe</a></H3>
<?php
  $file = "data/FF00E1A4.txt";
  $fh = fopen($file, "r");
  if (!$fh) {
      header('Location: input.php');
      exit;
  }
  $listing = false;
  $txt = fread($fh, filesize($file));
  fclose($fh);
  // $txt = mb_convert_encoding($txt, 'UTF-8', 'ISO-8859-1');
  $lines = explode("\n", $txt);
  foreach ($lines as $line) {
      $isshop = preg_match("/^# (.*)/", $line, $match);
      if ($isshop) {
        echo "<hr/><h2>$match[1]</h2>\n";
        $listing = true;
      } 
      else if ($listing && preg_match("/\S/", $line)) 
      {
          echo "<p><input type=checkbox> $line</p>";
      }
  }
?>
</body></html>
