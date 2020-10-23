<?php 
    include './header.php';
    $perfis_habilitados = array(1);
?>

        <div class="container">
            <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Listagem de Pessoas</h2>
             <div class="row float-right">
                 <h3><a class="float-right" style="color: black; text-decoration: none;" href="./cadpessoa.php" >Adicionar um novo usuário <i class="fa fa-plus-circle" aria-hidden="true"></i></a></h3>
            </div>
        <?php
            include './Pessoa.php';
            $itens_pagina = 10;
            $total = 0;
            $sql = "SELECT COUNT(*) as c FROM pessoa";
            $pdo = Conexao::banco();
            $sql = $pdo->prepare($sql);
            $sql->execute();
            $total = $sql->fetch(PDO::FETCH_ASSOC);
            $total = $total['c'];
            $paginas = $total / $itens_pagina;
            $p=0;
            $pg = 1;

            if(isset($_GET['p']) && !empty($_GET['p'])){
                $pg = addslashes($_GET['p']);
            }
            $p = ($pg -1) * $itens_pagina;
            $sql = "SELECT * FROM pessoa ORDER BY nome LIMIT $p, $itens_pagina";
            $sql = $pdo->query($sql);

            if ($sql->rowCount() > 0){
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome do Usuario</th>
                            <th>Documento</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                foreach ($sql->fetchAll() as $item) {
                    $opcoes = '';
                    echo "<tr>";
                        echo "<td>".$item['nome']."</td>";
                        echo "<td>";
                        echo ($item['doc_identificacao']);
                        echo "</td>";
                        echo "<td>";
                        echo ($item['email']);
                        echo "</td>";
                        echo "<td>";
                        echo ($item['telefone']);
                        echo "</td>";
                        $opcoes = "<td>"
                                . "<a style='color:black;' href='./cadpessoa.php?chave=".$item['id_pessoa']."'><i class='fa fa-pencil' aria-hidden='true'></i></a>&nbsp&nbsp&nbsp&nbsp"
                                . "<a style='color: black;' href='./alterarsenha.php?chave=".$item['id_pessoa']."'><i class='fa fa-key' aria-hidden='true'></i></a>&nbsp&nbsp&nbsp&nbsp";
                        if(in_array($_SESSION['perfil'], $perfis_habilitados))
                            $opcoes .= "<a style='color: black;' href='./supersenha.php?chave=".$item['id_pessoa']."'><i class='fa fa-superpowers' aria-hidden='true'></i></a>&nbsp&nbsp&nbsp&nbsp";
                        $opcoes .= "</td>";
                    echo $opcoes;
                    echo "</tr>";
                }
                ?>
                    </tbody>
                </table>
                <?php
                echo "<hr/>";
                /*for($q=0;$q<$paginas;$q++){
                    echo '<a href="./?p='.($q+1).'">[ '.($q+1).' ]</a>';
                }*/
                 echo '<nav aria-label="Navegaçao">';
                    echo '<ul class="pagination">';
                    $enable ='';
                    if (($pg-1)<=0)
                        $enable = 'disabled';
                    echo "<li class='page-item ". $enable ."'><a class='page-link' href='./listausuario.php?p=".($pg-1)."'>Anterior</a></li>";
                    echo "<li class='page-item'><a class='page-link' href='./listausuario.php?p=".$pg."'>".$pg."/".ceil($paginas)."</a></li>";
                    $enable ='';
                    if ($pg>=$paginas)
                        $enable = 'disabled';
                    echo "<li class='page-item ". $enable . "'><a class='page-link' href='./listausuario.php?p=".($pg+1)."'>Próximo</a></li>";
                    echo '</ul>';
                  echo '</nav>';
            }
        ?>
            </div>
    </body>
</html>