<?php
include './header.php';
include './Conexao.php';

if(isset($_POST['dtIncial']) && !empty($_POST['dtIncial']) && isset($_POST['dtFinal']) && !empty($_POST['dtFinal'])){
    $sql = "SELECT e.data_retirada as dtRetirada, 
                    p.nome as retirou,
                    c.descricao as sala, 
                    e.data_devolucao as dtDevolucao,
                    (SELECT nome FROM pessoa WHERE e.pessoa_id_devolucao = id_pessoa) as devolveu
                FROM emprestimo e, pessoa p, chave c
                WHERE e.chave_id = c.id_chave 
                AND e.pessoa_id_retirada = p.id_pessoa
                AND e.data_retirada BETWEEN :dtinicio AND :dtfim ";
    if (isset($_POST['sala']) && !empty($_POST['sala'])){
        // $sql .= "AND e.chave_id IN (:salas)";
        $sql .= "AND find_in_set(cast(e.chave_id as char), :salas)";
    }
    $sql .= " ORDER BY e.data_retirada";
    $pdo = Conexao::banco();
    $sql = $pdo->prepare($sql);
    $sql->bindValue(":dtinicio", $_POST['dtIncial'], PDO::PARAM_STR);
    $sql->bindValue(":dtfim", $_POST['dtFinal']." 23:59", PDO::PARAM_STR); //Concatenado a hora para pegar o dia fim todo 
    if (isset($_POST['sala']) && !empty($_POST['sala'])){
        $salas = implode(',', $_POST['sala']);
        $sql->bindValue(":salas", $salas, PDO::PARAM_STR);
    }
    $sql->execute();
   
}  
?>
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Data de retirada</th>
                <th>Quem retirou</th>
                <th>Sala</th>
                <th>Data de Devolução</th>
                <th>Quem devolveu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($sql->fetchAll() as $emprestimo){
                echo "<tr>";
                    $dataRetirada = strtotime($emprestimo['dtRetirada']);
                    $dataDevolucao = strtotime($emprestimo['dtDevolucao']);
                    $devolucao = !empty($dataDevolucao) ? date("d/m/Y G:i", $dataDevolucao) : $dataDevolucao;
                    echo "<td>" . date("d/m/Y G:i", $dataRetirada) ."</td>";
                    echo "<td>" . $emprestimo['retirou']  ."</td>";
                    echo "<td>" . $emprestimo['sala']  ."</td>";
                    echo "<td>" . $devolucao ."</td>";
                    echo "<td>" . $emprestimo['devolveu']  ."</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>