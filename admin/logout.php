<?php require_once("includes/header.php");

//Cerramos la sesion y redirigimos a la ventana de login:
$session->logout();
redirect("login.php");
?>