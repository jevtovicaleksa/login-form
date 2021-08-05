<?php 
session_start();
$_SESSION = array();// sve podesene parametre sesije stavi u prazan niz, tako ih ponistavamo
session_destroy();// prekidaju se sve sesije
header("Location:login.php");
exit();

 ?>