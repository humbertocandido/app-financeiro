<?php

namespace ODC\Modelos;

use ODC\Kernel\Conexao;
use ODC\Kernel\Exceptions;

/* Classe responsável por persistir dados na tabela usuarios */

class Usuario
{
    /* Armazena uma conexão com o banco de dados */
    private $con;
    /* Armazena o nome da tabela a ser conectada no banco de dados*/
    private $table = 'usuarios';
    /* Método construtor responsável por buscar uma conexão com o banco de dados no momento da criação do objeto*/
    public function __construct($db)
    {
        $this->con = Conexao::conecta($db);
    }

    /* Método responsável por gravar os dados da classe na tabela usuarios no banco de dados*/
    public function salvar($dados)
    {
        $sql = 'INSERT INTO usuarios 
                        (
                            nome, 
                            email, 
                            senha, 
                            ativo, 
                            cadastro
                        ) 
                VALUES (?,?,?,?,?)';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['nome']);
        $stmt->bindValue(2, $dados['email']);
        $stmt->bindValue(3, sha1($dados['senha']));
        $stmt->bindValue(3, false);
        $stmt->bindValue(5, date('Y-m-d H:i:s', time()));

        try {
            $stmt->execute();
            $dados['id'] = $this->con->lastInsertId();
            return $dados;
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    /* Método retorna um usuário se email e senha são iguais ao informado e se estiver ativo */
    public function verifica($dados)
    {
        $sql = 'SELECT 
                    id, 
                    nome, 
                    email, 
                    nivel 
                FROM usuarios 
                WHERE 
                        email = ? 
                    AND senha = ? 
                    AND ativo = 1 
                    Limit 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['email']);
        $stmt->bindValue(2, sha1($dados['senha']));

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método retorna todos os usuarios da tabela usuarios
    public function listaTodos()
    {
        $sql = 'SELECT 
                    id, 
                    nome, 
                    email, 
                    nivel, 
                    ativo, 
                    DATE_FORMAT(cadastro, \'%d-%m-%Y\') as cadastro
                FROM usuarios ORDER BY id DESC';

        $stmt = $this->con->prepare($sql);
        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método lista todos os usuários onde o campo nível = campo informado
    public function listaPorNivel($nivel)
    {
        $sql = 'SELECT 
                    id, 
                    nome, 
                    email, 
                    nivel, 
                    ativo, 
                    DATE_FORMAT(cadastro, \'%d-%m-%Y\') as cadastro,
                    (SELECT COUNT(*) FROM usuarios u1 WHERE u1.nivel = 50) as total
                FROM usuarios 
                WHERE nivel = ? 
                ORDER BY id DESC';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $nivel);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca todos usuários onde campo nivel = nivel informado e campo ativo = 1
    public function buscarPorNivel($nivel)
    {
        $sql = 'SELECT 
                    *
                FROM usuarios 
                WHERE nivel = ? AND ativo = ?
                ORDER BY id ASC';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $nivel);
        $stmt->bindValue(2, true);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca um usuário onde o campo email = email informado
    public function buscarPorEmail($email)
    {
        $sql = 'SELECT
                    id, 
                    nome, 
                    email,
                    nivel
                FROM usuarios 
                WHERE email = ? 
                LIMIT 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $email);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método retorna um usuário onde campo id = id informado
    public function retornaUsuario($id)
    {
        $sql = 'SELECT id, nome, email, nivel, ativo, DATE_FORMAT(cadastro, \'%d-%m-%Y\') as cadastro  
        FROM usuarios WHERE id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id);
        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método atualiza campo nivel e ativo onde campo id = id informado
    public function atualizarUsuario($dados)
    {
        $sql = 'UPDATE usuarios SET nivel = ?, ativo = ? WHERE id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['nivel']);
        $stmt->bindValue(2, $dados['estado']);
        $stmt->bindValue(3, $dados['id']);
        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método atualiza a senha de onde o campo id = id informado
    public function atualizarSenha($senha, $id)
    {
        $sql = 'UPDATE usuarios SET senha = ?, updated_at = NOW() WHERE id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, sha1($senha));
        $stmt->bindValue(2, $id);

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
}
