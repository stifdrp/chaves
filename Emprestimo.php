<?php
include './Conexao.php';

class Emprestimo
{
    public static function chaves_disponivies() {
        $pdo = Conexao::banco();
        $sql = "SELECT chave_id FROM emprestimo WHERE data_devolucao IS NULL";
        $sql = $pdo->prepare($sql);
        $sql->execute();
        $conjunto = array();
        if($sql->rowCount() > 0){
            foreach ($sql as $chave){
                array_push($conjunto, $chave['chave_id']);
            }
            $ids = join(',', $conjunto);
            $sql = "SELECT * FROM chave WHERE id_chave NOT IN ($ids) AND ativo = 1 ORDER BY descricao";
            $sql = $pdo->prepare($sql);
            $sql->execute();
            $dados = $sql->fetchAll();
            return $dados;
        } else {
            $sql = "SELECT * FROM chave WHERE ativo = 1 ORDER BY descricao";
            $sql = $pdo->prepare($sql);
            $sql->execute();
            $dados = $sql->fetchAll();
            return $dados;
        }
        return false;
    }
    
    public static function pegaDescricao($id) {
        $pdo = Conexao::banco();
        $sql = "SELECT descricao FROM chave WHERE id_chave = :id";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        if($sql->rowCount() > 0){
            $desc = $sql->fetch();
            return $desc['descricao'];
        }
        return FALSE;
    }
    
    public static function pegaDescricaoChaveReserva($reserva){
        $pdo = Conexao::banco();
        $sql = "SELECT descricao FROM chave WHERE id_chave = (SELECT chave_id FROM emprestimo WHERE id = :id)";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id", $reserva);
        $sql->execute();
        if($sql->rowCount() > 0){
            $desc = $sql->fetch();
            return $desc['descricao'];
        }
        return FALSE;
    }
    

    public static function validaLogin($login, $senha){
        $pdo = Conexao::banco();
        $sql = $pdo->prepare("SELECT * FROM pessoa WHERE doc_identificacao = :login");
        $sql->bindValue(":login", $login);
        $sql->execute();
    
        if($sql->rowCount()>0){
            $sql = $sql->fetch();
            if (password_verify($senha, $sql['senha'])){
                return $sql['id_pessoa'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function emprestar($operador, $solicitante, array $chaves, $observacao){
        $pdo = Conexao::banco();
        $sql = $pdo->prepare("SELECT id_pessoa FROM pessoa WHERE doc_identificacao = :login");
        $sql->bindValue(":login", $solicitante);
        $sql->execute();
        $dados = $sql->fetch();
        $restritas = array();
        $id_solicitante = $dados['id_pessoa'];
        foreach($chaves as $chave){
            if (Emprestimo::e_restrita($chave, $id_solicitante)){
                array_push($restritas, $chave);
            }
        }
        $permitidas = array_diff($chaves, $restritas);
        foreach($permitidas as $chave){
            $sql = $pdo->prepare("INSERT INTO emprestimo SET chave_id = :chave, data_retirada = NOW(), pessoa_id_retirada = :id_solicitante, operador_retirada = :operador, observacao = :observacao");
            $sql->bindValue(":chave", $chave);
            $sql->bindValue(":id_solicitante", $id_solicitante);
            $sql->bindValue(":operador", $operador);
            $sql->bindValue(":observacao", $observacao);
            $sql->execute();
        }
        $_SESSION['texto_chaves_restritas'] = null;
        if(count($restritas)>0){
            $texto = "Este usuário não tem permissão para retirar a(s) chave(s): ";
            foreach($restritas as $item){
                $texto .= Emprestimo::pegaDescricao($item). " - ";
            }
            $texto = substr($texto, 0, -3) .".\n";
            $_SESSION['texto_chaves_restritas'] = $texto;
        }
    }
    
    public static function devolver($operador, $devolutor, array $reservas, $observacao){
        $pdo = Conexao::banco();
        $sql = $pdo->prepare("SELECT id_pessoa FROM pessoa WHERE doc_identificacao = :login");
        $sql->bindValue(":login", $devolutor);
        $sql->execute();
        $dados = $sql->fetch();
        $id_devolutor = $dados['id_pessoa'];
        foreach($reservas as $reserva){
            $sql = $pdo->prepare("UPDATE emprestimo SET data_devolucao = NOW(), pessoa_id_devolucao = :id_devolutor, operador_devolucao = :operador, observacao = CONCAT(IFNULL(observacao,''), :observacao) WHERE id = :reserva" );
            $sql->bindValue(":reserva", $reserva);
            $sql->bindValue(":id_devolutor", $id_devolutor);
            $sql->bindValue(":operador", $operador);
            if($observacao != '') 
                $observacao = " || " . $observacao;
            $sql->bindValue(":observacao", $observacao);
            $sql->execute();
        }
    }
    
    public static function chaves_emprestadas() {
        $pdo = Conexao::banco();
        $sql = "SELECT c.descricao, e.id, p.nome, e.data_retirada, e.observacao  
                    FROM emprestimo e, chave c, pessoa p
                    WHERE e.chave_id = c.id_chave AND
                          e.pessoa_id_retirada = p.id_pessoa AND
                          e.data_devolucao IS NULL
                    ORDER BY c.descricao";
        $sql = $pdo->prepare($sql);
        $sql->execute();
        if($sql->rowCount() > 0){
            $dados = $sql->fetchAll();
            return $dados;
        }
        return NULL;
     }
     
     public function pegaNome($id){
        $pdo = Conexao::banco();
        $sql = "SELECT nome FROM pessoa WHERE id_pessoa = :id";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        if($sql->rowCount() > 0){
            $desc = $sql->fetch();
            return $desc['nome'];
        }
        return FALSE;
     }
     
     public static function e_restrita($chave, $pessoa){
        $pdo = Conexao::banco();
        $sql = "SELECT pessoa_id_pessoa as id FROM pessoas_autorizadas WHERE chave_id_chave = :chave";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":chave", $chave, PDO::PARAM_INT);
        $sql->execute();
        if($sql->rowCount() > 0){
            $pessoas = array();
            foreach($sql->fetchAll() as $pes) {
                array_push($pessoas, $pes['id']);
            }
            if(in_array($pessoa, $pessoas)){
                return FALSE;
            }
            return TRUE;
        } 
        return FALSE;
     }
}