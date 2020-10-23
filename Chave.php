<?php
/*
 * Classe da chave da sala de aula
 * 
 * @package SistemaDeChaves
 * @author TIFDRP <tifdrp@usp.br>
 * 
 * @property int        $id         Chave primaria.
 * @property string     $descricao  Nome da sala que a chave abre.
 * @property boolean    $ativo      A sala está habilitada a ser reservada
 *  
 */

include './Conexao.php';

class Chave {
    //Chave primaria
    private $id;
    private $descricao;
    private $ativo;
    
    public function carrega($id) {
        if (!empty($id)){
            $pdo = Conexao::banco();
            $sql = "SELECT * FROM chave WHERE id_chave = :id";
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
            if($sql->rowCount() > 0){
                $dados = $sql->fetch();
                $this->descricao = $dados['descricao'];
                $this->ativo = $dados['ativo'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
     public function setId($id){
        $this->id = $id;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }
    
    public function setDescricao($desc){
        $this->descricao = $desc;
    }
    
    public function getAtivo(){
        return $this->ativo;
    }
    
    public function setAtivo($ativo){
        if ($ativo) {
            $this->ativo = 1;
        } else {
            $this->ativo = 0;
        }
    }
    
    public function jaExiste() {
            $pdo = Conexao::banco();
            $sql = "SELECT * FROM chave WHERE descricao = :descricao";
            if (isset($this->id) && !empty($this->id)) {
                $sql = $sql . " AND id_chave != :id";
            }
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":descricao", $this->descricao, PDO::PARAM_STR);
            
            if (isset($this->id) && !empty($this->id)) {
                $sql->bindValue(":id", $this->id, PDO::PARAM_INT);
            }
            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }
            return false;
    }
    
    public function salvar($pessoas) {
        if(is_numeric($this->id)){
            if (!$this->jaExiste()){
                $pdo = Conexao::banco();
                $sql = "UPDATE chave SET descricao = :descricao, ativo = :ativo WHERE id_chave = :id";
                $sql = $pdo->prepare($sql);
                $sql->bindValue(":descricao", $this->descricao);
                $sql->bindValue(":ativo", $this->ativo);
                $sql->bindValue(":id", $this->id);
                $sql->execute();
                $this->atualiza_autorizadas($pessoas, $this->id);
            } else {
                echo "<script>alert('Sala já existe');</script>";
            }
                
        } else {
            if (!($this->jaExiste())){
                $pdo = Conexao::banco();
                $sql = "INSERT INTO chave SET descricao = :descricao, ativo = :ativo";
                $sql = $pdo->prepare($sql);
                $sql->bindValue(":descricao", $this->descricao);
                $sql->bindValue(":ativo", $this->ativo);
                $sql->execute();
                $nova = "SELECT MAX(id_chave) as id FROM chave";
                $nova = $pdo->prepare($nova);
                $nova->execute();
                $nova = $nova->fetch();
                $this->atualiza_autorizadas($pessoas, $nova['id']);
            } else {
                echo "<script>alert('Sala já existe');</script>";
            }
            
        }
    }
    
    public function delete() {
        $perfis_habilitados = array(1);
        if(in_array($_SESSION['perfil'], $perfis_habilitados)){
            $sql = "DELETE FROM chave WHERE id_chave = ?";
            $pdo = Conexao::banco();
            $sql = $pdo->prepare($sql);
            $sql->execute(array($this->id));
        } else {
            echo "Ola";
        }
    }
    
    public function atualiza_autorizadas($pessoas, $chave){
        $pdo = Conexao::banco();
        $sql = "DELETE FROM pessoas_autorizadas WHERE chave_id_chave = :id";
        $autorizadas = $pdo->prepare($sql);
        $autorizadas->bindValue(":id", $chave, PDO::PARAM_INT);
        $autorizadas->execute();
        if (count($pessoas) > 0) {
            foreach ($pessoas as $pessoa) {
                $novo = "INSERT INTO pessoas_autorizadas VALUES (:chave, :pessoa)";
                $novo = $pdo->prepare($novo);
                $novo->bindValue(":chave", $chave, PDO::PARAM_INT);
                $novo->bindValue(":pessoa", $pessoa, PDO::PARAM_INT);
                $novo->execute();
            }
        } 
    }
}

    
