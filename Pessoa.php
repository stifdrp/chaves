<?php
/*
 * Classe das pessoas que utilizarão o sistema de chaves da sala de aula
 * 
 * 
 * @package SistemaDeChaves
 * @author TIFDRP <tifdrp@usp.br>
 * 
 * @property int        $id         Chave primaria.
 * @property string     $nome       Nome do usuario.
 * @property string     $doc        RG ou RNE ou Numero USP do usuário
 * @property string     $email      Email do usuário
 * @property string     $senha      Senha do usuário
 * @property tinyint    $perfil     Qual é o perfil do usuario 0 - desabilitado, 1 - administrador
 *                                          2 - operador, 3 - usuario
 * @property string     $telefone   Telefone do usuário
 * @method void setEmail(string $string)   Atribui o email
 *  
 */

include './Conexao.php';

class Pessoa {
    private $id;
    private $nome;
    private $doc;
    private $email;
    private $senha;
    private $perfil;
    private $telefone;
    
    public function carrega($i) {
        if(!empty($i)){
            $pdo = Conexao::banco();
            $sql = "SELECT * FROM pessoa WHERE id_pessoa = ?";
            $sql = $pdo->prepare($sql);
            $sql->execute(array($i));
            if($sql->rowCount() > 0){
                $data = $sql->fetch();
                $this->id = $data['id_pessoa'];
                $this->nome = $data['nome'];
                $this->doc = $data['doc_identificacao'];
                $this->email = $data['email'];
                //$this->senha = $data['senha'];
                $this->perfil = $data['perfil'];
                $this->telefone = $data['telefone'];
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    
    public function setId($id) {
        $this->id = intval($id);
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setDoc($doc) {
        $this->doc = $doc;
    }
    
    public function getDoc() {
        return $this->doc;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getSenha() {
        return $this->senha;
    }
    
    public function setSenha($senha) {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }
    
    public function getPerfil() {
        return $this->perfil;
    }
    
    public function setTelefone($tel) {
        $this->telefone = $tel;
    }
    
    public function getTelefone() {
        return $this->telefone;
    }

    public function salvar() {
        if(!empty($this->id)){
            //atualizar usuario
            $pdo = Conexao::banco();
            // if ($_SESSION['perfil'] == 1 && isset($this->senha) && !empty($this->senha)){
            //     $sql = "UPDATE pessoa SET email = ?, nome = ?, doc_identificacao = ?, perfil = ?, telefone = ?  WHERE id_pessoa = ?";
            //     $sql = $pdo->prepare($sql);
            //     $sql->execute(array(
            //         $this->email,
            //         $this->nome,
            //         $this->doc,
            //         $this->perfil,
            //         $this->telefone,
            //         $this->id));
            //     return true;
            // } else {
                $sql = "UPDATE pessoa SET email = ?, nome = ?, doc_identificacao = ?, perfil = ?, telefone = ?, senha = ?  WHERE id_pessoa = ?";
                $sql = $pdo->prepare($sql);
                $sql->execute(array(
                    $this->email,
                    $this->nome,
                    $this->doc,
                    $this->perfil,
                    $this->telefone,
                    $this->senha,
                    $this->id));
                return true;
//            }
        }else{
	    //adicionar usuario novo
	    if (!$this->jaExiste()) {
	            $pdo = Conexao::banco();
        	    $sql = "INSERT INTO pessoa SET email = ?, senha = ?, nome = ?, doc_identificacao = ?, perfil = ?, telefone = ?";
	            $sql = $pdo->prepare($sql);
        	    $sql->execute(array(
	                $this->email,
        	        $this->senha,
                	$this->nome,
	                $this->doc,
        	        $this->perfil,
                	$this->telefone));
	        return true;
	    }
	}
        return false;
    }
    
    public function delete() {
        $sql = "DELETE FROM usuarios WHERE id_pessoa = ?";
        $sql = $this->pdo->prepare($sql);
        $sql->execute(array($this->id));
        
    }
    
     public function salvarSenha() {
        if(!empty($this->id)){
            //atualizar usuario
            $pdo = Conexao::banco();
            $sql = "UPDATE pessoa SET senha = ? WHERE id_pessoa = ?";
            $sql = $pdo->prepare($sql);
            $sql->execute(array(
                $this->senha,
                $this->id));
            return true;
        }
     }

    public function jaExiste() {
        $pdo = Conexao::banco();
        $sql = "SELECT * FROM pessoa WHERE doc_identificacao = ?";
        $sql = $pdo->prepare($sql);
        $sql->execute(array(
                $this->doc
            ));
        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }
    
}
