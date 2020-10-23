<?php

class Conexao {
    private static $pdo;
    
    // Impede que a classe seja instanciada
    private function __construct() { }

    // Impede que a classe seja  clonada
    private function __clone() { }

    //Impede a utilização do Unserialize (que a variavel retorne o array original)
    private function __wakeup() { }

    public static function banco() {
        try{
            self::$pdo = new PDO("mysql:dbname=chaves;host=localhost","admin","admin");
        } catch (PDOException $e) {
            echo "Fail: ".$e->getMessage();
        }
        return self::$pdo;
    }
}
?>
