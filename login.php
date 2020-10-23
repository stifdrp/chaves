<?php
session_start();
require './Conexao.php';
if(isset($_POST['login']) && !empty($_POST['login'])){
    $login = addslashes(intval($_POST['login']));
    $senha = addslashes($_POST['senha']);
    $pdo = Conexao::banco();
    $sql = $pdo->prepare("SELECT * FROM pessoa WHERE doc_identificacao = :login");
    $sql->bindValue(":login", $login);
    $sql->execute();

    if($sql->rowCount()>0){
        $sql = $sql->fetch();
        $perfis_habilitados = array(1,2);
        if(in_array($sql['perfil'], $perfis_habilitados)){
            if (password_verify($senha, $sql['senha'])){
                $_SESSION['id'] = $sql['id_pessoa'];
                $_SESSION['nome'] = $sql['nome'];
                $_SESSION['perfil'] = $sql['perfil'];
                header("Location: selecionar_chaves.php");
                exit();
            } 
        }
    }
    session_destroy();
    header("Location: login.php");
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
        <link rel="stylesheet" href="assets/css/signin.css" />
        <script type="text/javascript" src="assets/js/popper.js"></script>
        <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <form class="form-signin"method="POST">
                <h2 class="form-signin-heading"><center>Sistema de reserva de chaves</center></h2>
                <label class="sr-only" for="login">Identificação</label>
                <input class="form-control" id="login" type="text" name="login" required="" placeholder="Identificação" autofocus=""/>
                <label class="sr-only" for="senha">Senha</label>
                <input class="form-control" id="senha" type="password" name="senha" placeholder="Senha" required="" /><br/>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            </form>
        </div>
    </body>
</html>
