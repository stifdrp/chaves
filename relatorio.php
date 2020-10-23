<?php 
    include './header.php';
    include './Chave.php';

    $sql = "SELECT id_chave, descricao FROM chave ORDER BY descricao";
    $pdo = Conexao::banco();
    $sql = $pdo->prepare($sql);
    $sql->execute();
?>
        <div class="container-fluid">
            <div class="container">
                <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Relatório</h2>
                <form method="POST" action="resultado.php">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="dtIncial">Data Inicial</label>
                        <input type="date" class="form-control" id="dtIncial" name="dtIncial" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dtFinal">Data Final</label>
                        <input type="date" class="form-control" id="dtFinal" name="dtFinal" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="sala">Sala(s)</label>
                        <select id="sala" name="sala[]" data-placeholder="Salas" class="chosen-select form-control" multiple style="width:350px;" tabindex="4">
                            <option value=""></option> 
                            <?php 
                                foreach ($sql->fetchAll() as $sala) {
                                    echo '<option value="'. $sala["id_chave"] . '">'. $sala["descricao"] .'</option>';
                                }
                            ?> 
                        </select>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" type="submit">OK</button>
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
    </body>
</html>

