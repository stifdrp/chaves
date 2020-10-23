<?php
include './header.php';
require './Chave.php';

if(isset($_GET['chave']) && !empty($_GET['chave'])){
    $chave = new Chave();
    if (!$chave->carrega($_GET['chave'])){
        header('Location: ./listachave.php');
    }
}

if(isset($_POST['descricao']) && !empty($_POST['descricao'])){
    $chave = new Chave();
    $chave->setDescricao(addslashes($_POST['descricao']));
    if(isset($_POST['ativa']) && !empty($_POST['ativa'])){
        $chave->setAtivo(1);
    } else {
        $chave->setAtivo(0);
    }
    if(isset($_GET['chave']) && !empty($_GET['chave'])){
        $chave->setId($_GET['chave']);
    }
    
    if(isset($_POST['pessoas']) && !empty($_POST['pessoas'])){
        $pessoas = $_POST['pessoas'];
    }

    $chave->salvar($pessoas);
        
    header("Location: listachave.php");
}

$sql = "SELECT id_pessoa, nome FROM pessoa";
$pdo = Conexao::banco();
$sql = $pdo->prepare($sql);
$sql->execute();

$perfis_habilitados = array(1);

?>
<?php
if(in_array($_SESSION['perfil'], $perfis_habilitados)){
?>
    <div class="container-fluid">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Cadastro de Sala</h2>
            <form method="POST">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="descricao">Nome da Sala:</label>
                    <input class="form-control col-4" type="text" name="descricao" id="descricao" placeholder="Nome da Sala" required="TRUE" value="<?php echo (isset($chave) && !empty($chave))?$chave->getDescricao():""; ?>" />
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="ativa">Sala Ativa?</label>
                    <input type="checkbox" id="ativa" name="ativa" value="True" 
                        <?php if(isset($chave) && !empty($chave)) {
                                echo ($chave->getAtivo()?"checked='true'":"");
                            } else {
                                echo "checked='true'";
                            }
                        ?> 
                    />
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="restricao">Esta sala somente pode utilizada por:</label>
                    <select id="pessoas" name="pessoas[]" data-placeholder="Pessoas" class="chosen-select form-control col-4" multiple style="width:350px;" tabindex="4">
                        <option value=""></option> 
                        <?php 
                            foreach ($sql->fetchAll() as $pessoa) {
                                echo '<option value="'. $pessoa["id_pessoa"] . '">'. $pessoa["nome"] .'</option>';
                            }
                        ?> 
                    </select>
                </div>
                <button class="btn btn-lg btn-primary" type="submit"><?php echo (isset($chave) && !empty($chave))?"Atualizar":"Cadastrar"; ?></button>
            </form>
        </div>
    </div>
        <link rel="stylesheet" href="assets/css/style_chosen.css" />
        <link rel="stylesheet" href="assets/css/prism.css" />
        <link rel="stylesheet" href="assets/css/chosen.min.css" />
        <script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/prism.js"></script>
        <script type="text/javascript" src="assets/js/init.js"></script>
        <script type='text/javascript'>
            $(document).ready(function(){
                $(".chosen-select").chosen();
                <?php
                    if (isset($_GET['chave']) && !empty($_GET['chave'])) {
                        $sql = "SELECT * FROM pessoas_autorizadas WHERE chave_id_chave = :id";
                        $pdo = Conexao::banco();
                        $autorizadas = $pdo->prepare($sql);
                        $autorizadas->bindValue(":id", $_GET['chave'], PDO::PARAM_INT);
                        $autorizadas->execute();
                        $pessoas = array();
                        foreach ($autorizadas->fetchAll() as $pessoa) {
                            array_push($pessoas, $pessoa['pessoa_id_pessoa']);
                        }
                        $pes = implode(',', $pessoas);
                        echo '$(".chosen-select").val(['.$pes.']).trigger("chosen:updated");';
                    }
                ?>
            });
        </script>
<?php 
} else {
    echo "Somente para administradores";
}
?>
    </body>
</html>