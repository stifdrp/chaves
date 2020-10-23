<?php
include './header.php';
require './Pessoa.php';
if(isset($_POST['password']) && !empty($_POST['password'])){
    if(isset($_GET['chave']) && !empty($_GET['chave'])){
        $pessoa = new Pessoa();
        if (!$pessoa->carrega($_GET['chave'])){
            header('Location: ./listausuario.php');
        }
        $pessoa->setSenha(addslashes($_POST['password']));
        $pessoa->salvarSenha();
    }
    header("Location: ./listausuario.php");    
}
$perfis_habilitados = array(1);
?>

<div class="container">
    <?php 
        if(in_array($_SESSION['perfil'], $perfis_habilitados)){
    ?>
    <h2 style="text-align: center; margin-bottom: 1.5rem;">Alterar Senha</h2>
    <form method="POST" id="FormSenha" name="FormSenha">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="password">Nova senha:</label>
            <input class="form-control col-4" type="password" id="password" name="password" placeholder="Nova senha" required="TRUE"/>
        </div>
        <button class="btn btn-lg btn-primary" type="submit">Alterar</button>
    </form>
    <?php 
        } else {
            echo "Não possui permissão para esse procedimento";
        }
    ?>
</div>
    </body>
</html>
