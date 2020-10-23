<?php 
    include './header.php';
    include './Chave.php';
    if(isset($_GET['chave']) && !empty($_GET['chave'])){
        $chave = new Chave();
        if (!$chave->carrega($_GET['chave'])){
            header('Location: ./listachave.php');
        }
        $chave->delete();
    } else {
        header('Location: ./listachave.php');
    }