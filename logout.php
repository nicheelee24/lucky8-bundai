<?php
session_start();
//$_SESSION["storedSecrect"]="";
//unset($_SESSION["qrscanned"]);
//unset($_SESSION['storedSecrect']);
unset($_SESSION["utype"]);
header('Location: index.php ',true);
exit();
?>