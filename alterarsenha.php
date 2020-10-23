<?php
include './header.php';
require './Pessoa.php';
if(isset($_POST['old']) && !empty($_POST['old']) && isset($_POST['nova']) && !empty($_POST['nova']) && isset($_POST['nova2']) && !empty($_POST['nova2'])){
   
    if( ($_POST['nova'] == $_POST['nova2'])){ 
        $pessoa = new Pessoa();
        $pessoa->carrega($_SESSION['id_altera_senha']);
        $senha = addslashes($_POST['old']);
        $novaSenha = addslashes($_POST['nova']);
        if (password_verify($senha, $pessoa->getSenha())){
            $pessoa->setSenha($novaSenha);
            $pessoa->salvarSenha();
            $_SESSION['id_altera_senha'] = NULL;
            header("Location: ./listausuario.php");
        }
        $alert = '<script type="text/javascript">alert("SENHA DO USUÁRIO NÃO CONFERE.");</script>';
    }else{
        $alert = '<script type="text/javascript">alert("SENHAS DIFERENTES!\nFAVOR CONFIRMAR A NOVA SENHA");</script>';
    }
    $_SESSION['id_altera_senha'] = NULL;
    unset($_POST);
    echo $alert;
}

if(isset($_GET['chave']) && !empty($_GET['chave'])){
    $pessoa = new Pessoa();
    if (!$pessoa->carrega($_GET['chave'])){
        header('Location: ./listausuario.php');
    }
    $_SESSION['id_altera_senha'] = $_GET['chave'];
}
?>

<div class="container">
    <h2 style="text-align: center; margin-bottom: 1.5rem;">Alterar Senha</h2>
    <form method="POST" id="FormSenha" name="FormSenha">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="old">Senha atual:</label>
            <input class="form-control col-4" type="password" id="old" name="old" placeholder="Senha atual" required="TRUE"/>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nova">Nova Senha:</label>
            <input class="form-control col-4" type="password" id="nova" name="nova" placeholder="Nova senha" required="TRUE"/>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="nova2">Repetir Senha:</label>
            <input class="form-control col-4" type="password" id="nova2" name="nova2" placeholder="Repetir senha" required="TRUE"/>
        </div>
        <button class="btn btn-lg btn-primary" type="submit" onclick="return validarSenha()">Alterar</button>
    </form>
        </div>
    </body>
    <script type="text/javascript">
    function validarSenha(){
        if (document.getElementById('nova').value != document.getElementById('nova2').value){
            alert("SENHAS DIFERENTES!\nFAVOR DIGITAR SENHAS IGUAIS");
            document.getElementById('nova2').focus();
            return false;
        }else{
            document.FormSenha.submit();
        }
    }
    </script>
</html>
