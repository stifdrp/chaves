<?php
session_start();
if(!(isset($_SESSION['perfil']) && !empty($_SESSION['perfil']))){
    header("Location: login.php");
    exit;
} 
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Sistemas de Chaves</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/font-awesome.css" />
        <link rel="stylesheet" href="assets/css/style.css" />
        <script type="text/javascript" src="assets/js/popper.js"></script>
        <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.mask.min.js"></script>
    </head>
    <body>
        <?php        include './menu.php';
