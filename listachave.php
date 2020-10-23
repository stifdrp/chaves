<?php
    include './header.php';
    $perfis_habilitados = array(1);
?>

        <div class="container">
        <?php
        if(in_array($_SESSION['perfil'], $perfis_habilitados)){
        ?>
            <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Listagem de Salas</h2>
            <div class="row float-right">
                <h3><a class="float-right" style="color: black; text-decoration: none;" href="./cadchave.php" >Adicionar uma nova chave <i class="fa fa-plus-circle" aria-hidden="true"></i></a></h3>
            </div>
            <?php
            include './Chave.php';
            $itens_pagina = 10;
            $total = 0;
            $sql = "SELECT COUNT(*) as c FROM chave";
            $pdo = Conexao::banco();
            $sql = $pdo->prepare($sql);
            $sql->execute();
            $total = $sql->fetch(PDO::FETCH_ASSOC);
            $total = $total['c'];
            $paginas = $total / $itens_pagina;
            $pg = 1;

            if(isset($_GET['p']) && !empty($_GET['p'])){
                $pg = addslashes($_GET['p']);
            }
            $p = ($pg - 1) * $itens_pagina;
            $sql = "SELECT * FROM chave ORDER BY descricao LIMIT $p, $itens_pagina";
            $sql = $pdo->query($sql);

            if ($sql->rowCount() > 0){
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome da Sala</th>
                            <th>Ativa</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                foreach ($sql->fetchAll() as $item) {
                    echo "<tr>";
                        echo "<td>".$item['descricao']."</td>";
                        echo "<td>";
                        echo ($item['ativo'] == 1)?"Sim":"Não";
                        echo "</td>";
                        echo "<td><a style='color:black;' href='./cadchave.php?chave=".$item['id_chave']."'><i class='fa fa-pencil' aria-hidden='true'></i></a>&nbsp&nbsp&nbsp&nbsp"
                                . "<a style='color: black;' href='./deletachave.php?chave=".$item['id_chave']."'><i class='fa fa-close' aria-hidden='true'></i></a></td>";
                    echo "</tr>";
                }
                ?>
                    </tbody>
                </table>
                <hr/>
                <?php
                /*for($q=0;$q<$paginas;$q++){
                    echo '<a style="color:black; text-decoration: none;" href="./listachave.php?p='.($q+1).'">[ '.($q+1).' ]</a>';
                }*/
                echo '<nav aria-label="Navegaçao">';
                    echo '<ul class="pagination">';
                    //(($p-1) > 0)?"":"disabled"
                    $enable ='';
                    if (($pg-1)<=0)
                        $enable = 'disabled';
                    echo "<li class='page-item ". $enable ."'><a class='page-link' href='./listachave.php?p=".($pg-1)."'>Anterior</a></li>";
                    echo "<li class='page-item'><a class='page-link' href='./listachave.php?p=".$pg."'>".$pg."/".ceil($paginas)."</a></li>";
                    $enable ='';
                    if ($pg>=$paginas)
                        $enable = 'disabled';
                    echo "<li class='page-item ". $enable . "'><a class='page-link' href='./listachave.php?p=".($pg+1)."'>Próximo</a></li>";
                    echo '</ul>';
                echo '</nav>';
            }
        } else {
            echo "Somente para administradores";
        }
        ?>
        
            </div>
    </body>
</html>