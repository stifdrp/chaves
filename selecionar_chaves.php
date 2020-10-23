<?php 
    include './header.php';
    include './Emprestimo.php';
    
    $chaves_disponíveis = Emprestimo::chaves_disponivies();
    $_SESSION['chaves'] = NULL;

    ?>
<div class="container-fluid">
    <div class="modal-body row">
        <div class="col-md-6">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Salas Disponíveis</h2>
            <form method="POST" action="./solicitante.php">
                <div >
                <?php
                    if($chaves_disponíveis){
                        $bloco = substr($chaves_disponíveis[0]['descricao'], 0,1);
                        foreach ($chaves_disponíveis as $chave){
                            if ($bloco == substr($chave['descricao'], 0,1)){
                                echo '<label class="btn btn-primary '. $bloco .' active" style="margin-left: 5px;">';
                                    echo '<input type="checkbox" name="chaves[]" id="chave" autocomplete="off" value="'. $chave['id_chave'] .'" /> '. $chave['descricao'];
                                echo '</label>';
                            } else {
                                echo '</div>';
                                $bloco = substr($chave['descricao'], 0,1);
                                echo '<div style="margin-top: 10px;">';
                                echo '<label class="btn btn-primary '. $bloco .' active" style="margin-left: 5px;">';
                                    echo '<input type="checkbox" name="chaves[]" id="chave" autocomplete="off" value="'. $chave['id_chave'] .'" /> '. $chave['descricao'];
                                echo '</label>';
                            }
                        }
                    }
                    ?>
                </div>
                
                <button class="btn btn-lg btn-primary" style="margin-top: 35px;" type="submit">Reservar</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Salas Ocupadas</h2>
            <?php 
                $dados = Emprestimo::chaves_emprestadas();
                $_SESSION['reservas'] = NULL;
                
                if(!empty($dados)){
                    $bloco = substr($dados[0]['descricao'], 0,1);
                    ?>
                    <form method="POST" action="./devolutor.php">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome da Sala</th>
                                    <th>Quem retirou</th>
                                    <th>Data da retirada</th>
                                     <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($dados as $item) {
                                    $desc = $item['descricao'];
                                    echo "<tr>";
                                        echo "<td>";
                                                
                                                $bloco = substr($desc, 0,1);
                                                echo '<label class="btn btn-primary '. $bloco .' active" style="margin-left: 5px;">';
                                                    echo '<input type="checkbox" name="reservas[]" id="reserva" autocomplete="off" value="'. $item['id'] .'" /> '. $desc;
                                                echo '</label>';
                                        echo "</td>";
                                        echo "<td>" . $item['nome'] ."</td>";
                                        $php_date = strtotime($item['data_retirada']);
                                        echo "<td>" . date("d/m/Y G:i", $php_date) ."</td>";
                                        echo "<td>" . $item['observacao'] ."</td>";
					$passouDia = (date("Y-m-d") > date("Y-m-d", $php_date)) ? "<div class=sinal></div>" : "";
                                        echo "<td>" . $passouDia . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <button class="btn btn-lg btn-primary" style="margin-top: 35px;" type="submit">Devolver</button>
                    </form>
            <?php
                }else {
                    echo '<h2>Não há salas alocadas</h2>';
                }
                ?>
        </div>
    </div>
</div>
