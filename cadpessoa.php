<?php
include './header.php';
require './Pessoa.php';
if(isset($_POST['doc']) && !empty($_POST['doc'])){
    $pessoa = new Pessoa();
    $pessoa->setDoc(addslashes($_POST['doc']));
    $pessoa->setEmail(addslashes($_POST['email']));
    $pessoa->setNome(addslashes($_POST['nome']));
    $pessoa->setSenha(addslashes($_POST['senha']));
    $pessoa->setTelefone(addslashes($_POST['telefone']));
    if(isset($_POST['perfil']) && !empty($_POST['perfil'])){
        $pessoa->setPerfil(addslashes($_POST['perfil']));
    } else {
        $pessoa->setPerfil(3);
    }

    if(isset($_SESSION['id_pessoa']) && !empty($_SESSION['id_pessoa'])){
        $pessoa->setId($_SESSION['id_pessoa']);
    }
    $pessoa->salvar();
    $_SESSION['id_pessoa'] = null;
    header("Location: ./listausuario.php");
}

if(isset($_GET['chave']) && !empty($_GET['chave'])){
    $pessoa = new Pessoa();
    if (!$pessoa->carrega($_GET['chave'])){
        header('Location: ./listausuario.php');
    }
    $_SESSION['id_pessoa'] = $_GET['chave'];
}

$perfis_habilitados = array(1);

?>
<script type='text/javascript'>
$(document).ready(function(){
    $("#telefone").mask("(99) 99999-9999");
});
</script>
        <div class="container-fluid">
            <div class="container">
                <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Cadastro de Pessoas</h2>
            <form method="POST">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="doc">Numero USP ou RG ou RNE:</label>
                    <input class="form-control col-4" type="number" name="doc" id="doc" placeholder="Numero USP ou RG ou RNE" required="TRUE" value="<?php echo (isset($pessoa) && !empty($pessoa))?$pessoa->getDoc():""; ?>" />
                </div>
                <div class="form-group row">
                     <label class="col-sm-4 col-form-label" for="nome">Nome:</label>
                    <input class="form-control col-4" type="text" name="nome" id="nome" placeholder="Nome" required="TRUE" value="<?php echo (isset($pessoa) && !empty($pessoa))?$pessoa->getNome():""; ?>" />
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="email">Email:</label>
                    <input class="form-control col-4" type="text" id="email" name="email" placeholder="email@dominio.com"  value="<?php echo (isset($pessoa) && !empty($pessoa))?$pessoa->getEmail():""; ?>" />
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="telefone">Telefone:</label>
                    <input class="form-control col-2" type="text" id="telefone" name="telefone" placeholder="(99) 99999-9999" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" value="<?php echo (isset($pessoa) && !empty($pessoa))?$pessoa->getTelefone():""; ?>" />
                </div>
                <?php 
                    if(!isset($_GET['chave']) ){
                ?>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="password">Senha:</label>
                        <input class="form-control col-2" type="password" id="password" name="senha" placeholder="Senha" value="" />
                    </div>
                <?php } ?>
                
                <?php 
                    if(in_array($_SESSION['perfil'], $perfis_habilitados)){
                ?>
                        <div class="form-group row">
                            <?php 
                                if(isset($pessoa) && !empty($pessoa)){
                                    $perfil_pessoa = $pessoa->getPerfil();
                                } else {
                                $perfil_pessoa = 3; //Perfil de usuário padrão; 
                                }
                            ?>
                            <label class="col-sm-4 col-form-label" for="perfil">Perfil:</label>
                            <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="perfil" id="perfil" placeholder="" required="TRUE"/>
                                
                                <option value="0" <?php echo ($perfil_pessoa == 0)?"selected":""; ?>>Desabilitado</option>
                                <option value="1" <?php echo ($perfil_pessoa == 1)?"selected":""; ?>>Administrador</option>
                                <option value="2" <?php echo ($perfil_pessoa == 2)?"selected":""; ?>>Operador</option>
                                <option value="3" <?php echo ($perfil_pessoa == 3)?"selected":""; ?>>Usuário</option>
                            </select>
                        </div>
                    <?php 
                    } ?>
                <button class="btn btn-lg btn-primary" type="submit"><?php echo (isset($pessoa) && !empty($pessoa))?"Atualizar":"Cadastrar"; ?></button>
            </form>
            </div>
        </div>
    </body>
</html>
