<?php

namespace ODC\Kernel;

class ConexaoPadrao
{
    /* Variável responsável por guardar uma conexão com o banco de dados */
    private static $con;

    /* Método responsável por retornar uma conexão caso já exista ou cria uma e retorna caso não exista */
    public static function conecta()
    {
        if (self::$con == null) {
            try {
                self::$con = new \PDO(
                    'mysql:host='.HOST.':3306;dbname=empresas',
                     USER,
                     PASS,
                    /*Nessa linha estou forçando o PDO a usar o charset UTF-8 para não ter problemas de 
                    codificação de caracteres*/
                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
                /* Configurando o PDO para disparar exceções quando acontecer algum erro ao persistir dados no banco */
                self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$con->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            } catch (\Exception $e) {
                echo "Erro ao tentar uma conexão ao banco de dados. <br> " . $e->getMessage();
                die;
            }
        }

        return self::$con;
    }
}
