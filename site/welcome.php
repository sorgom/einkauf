<?php
require_once('body.php');
require_once('dtxt.php');
?>
<div class=itxt>Willkommen.

Du benötigst nur einem Link, um loszulegen.

An welche E-Mail-Adresse sollen wir deinen Link senden?
</div>
<form action=mail.php method=GET>
    <div class=center><input type=email name=em id=em class=em autofocus required placeholder='your email'></div>
    <div class=center><input type=submit class='i enter' value=''></div>
</form>
<div class=itxt>Deine E-Mail-Adresse wird nirgens gespeichert.</div>
