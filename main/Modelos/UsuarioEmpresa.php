<?php

namespace ODC\Modelos;

use DateTime;
use ODC\Kernel\ConexaoPadrao;
use ODC\Kernel\Exceptions;

class UsuarioEmpresa
{
    /* Armazena uma conexão com o banco de dados */
    private $con;
    /* Método construtor responsável por buscar uma conexão com o banco de dados no momento da criação do objeto*/
    public function __construct()
    {
        $this->con = ConexaoPadrao::conecta();
    }

    /* Método retorna um usuário se email e senha são iguais ao informado e se estiver ativo */
    public function verifica($dados)
    {
        $sql = 'SELECT 
                    u.id,
                    u.nome,
                    pj.bd,
                    nivel,
                    pj.nome_fantasia 
                FROM usuarios u
                LEFT JOIN pj_usuario_link ul ON(ul.usuario_id = u.id)
                LEFT JOIN pj ON (pj.id = ul.pj_id )
                WHERE 
                    u.email = ? 
                AND u.senha = ? 
                AND ul.situacao = 1
                ORDER BY pj.nome_fantasia ;';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['email']);
        $stmt->bindValue(2, sha1($dados['senha']));

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function verificaPorId($id, $senha)
    {
        $sql = 'SELECT 
                    *
                FROM empresas.usuarios u
                WHERE 
                    u.id = ? 
                AND u.senha = ?;';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, sha1($senha));

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca na tabela pj no banco empresas retornando um registro onde o campo usuario_id = recebido como parâmetro 
    public function empresasUsuario($id)
    {
        $sql = 'SELECT 
                    u.id,
                    u.nome,
                    pj.bd,
                    pj.nome_fantasia 
                FROM usuarios u
                LEFT JOIN pj_usuario_link ul ON(ul.usuario_id = u.id)
                LEFT JOIN pj ON (pj.id = ul.pj_id )
                WHERE 
                    ul.usuario_id = ?  
                AND ul.situacao = 1
                ORDER BY pj.nome_fantasia ;';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca na tabela pj no banco empresas retornando um registro onde o campo bd = recebido como parâmetro 
    public function buscaEmpresa($banco)
    {
        $sql = 'SELECT 
                    *
                FROM pj
                WHERE bd like ? LIMIT 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $banco);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function buscaTurnos($banco)
    {
        $sql = 'SELECT 
                    DATE_FORMAT(turno_1_inicio, \'%H\') manha_inicio_h,
                    DATE_FORMAT(turno_1_inicio, \'%i\') manha_inicio_m,
                    DATE_FORMAT(turno_1_fim, \'%H\') manha_fim_h,
                    DATE_FORMAT(turno_1_fim, \'%i\') manha_fim_m,
                    DATE_FORMAT(turno_2_inicio, \'%H\') tarde_inicio_h,
                    DATE_FORMAT(turno_2_inicio, \'%i\') tarde_inicio_m,
                    DATE_FORMAT(turno_2_fim, \'%H\') tarde_fim_h,
                    DATE_FORMAT(turno_2_fim, \'%i\') tarde_fim_m
                FROM pj
                WHERE bd like ? LIMIT 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $banco);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método grava um registro na tabela usuarios no banco de dados empresas
    public function salvar($dados)
    {
        $sql = "INSERT INTO usuarios 
                        (
                            nome,
                            senha,
                            email,
                            dt_cadastro
                        )
                VALUES (?,?,?,?);";
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['nome']);
        $stmt->bindValue(2, sha1($dados['senha']));
        $stmt->bindValue(3, $dados['email']);
        $stmt->bindValue(4, (new DateTime('now'))->format('Y-m-d H:i:s'));

        try {
            
            if ($stmt->execute()) {
                $dados['id'] = $this->con->lastInsertId();
                $dados['pj_id'] = 1;
                return $this->linkaUsuarioBD($dados);
            }

            return false;
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método faz uma consulta na tabela usuários do banco empresas onde campo email = dados recebido como parâmetro
    public function temEmail($email)
    {
        $sql = 'SELECT 
                    *
                FROM usuarios
                WHERE email = ? LIMIT 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $email);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca na tabela usuarios relacionando com as tabelas 
    public function buscaUsuariosBD($bd)
    {
        $sql = 'SELECT 
                    u.id id_usuario,
                    u.nome,
                    u.email,
                    DATE_FORMAT(pl.data_insert, \'%d-%m-%Y\') cadastro,
                    pl.id id_link,
                    pl.situacao,
                    pl.nivel
                FROM usuarios u
                INNER JOIN pj_usuario_link pl ON(pl.usuario_id = u.id)
                INNER JOIN pj ON(pj.id = pl.pj_id )
                WHERE 
                    pj.bd = ?
                    AND pj.id != 1';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $bd);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca na table pj_usuario_link do banco empresas onde campo id = id recebido como parâmetro
    public function buscaPorLink($id)
    {
        $sql = 'SELECT 
                    u.id id_usuario,
                    u.nome,
                    u.email,
                    DATE_FORMAT(pl.data_insert, \'%d-%m-%Y\') cadastro,
                    pl.id id_link,
                    pl.situacao,
                    pl.nivel
                FROM usuarios u
                INNER JOIN pj_usuario_link pl ON(pl.usuario_id = u.id)
                INNER JOIN pj ON(pj.id = pl.pj_id )
                WHERE pl.id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método atualiza a tabela pj_usuario_link os dados recebidos como parâmetro onde o campo id = id recebido
    public function atualizarUsuario($dados)
    {
        $sql = 'UPDATE pj_usuario_link 
                SET 
                    nivel = ?, 
                    situacao = ? 
                WHERE id = ?';

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

    // Método grava os dados recebidos como parâmetro na tabela pj_usuario_link 
    public function linkaUsuarioBD($dados)
    {
        $sql = 'INSERT INTO pj_usuario_link 
                            (   
                                usuario_id,
                                pj_id,
                                nivel,
                                situacao,
                                data_insert
                            )
                VALUES (?,?,?,?,?); ';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['id'], \PDO::PARAM_INT);
        $stmt->bindValue(2, $dados['pj_id'], \PDO::PARAM_INT);
        $stmt->bindValue(3, 1, \PDO::PARAM_INT);
        $stmt->bindValue(4, 1, \PDO::PARAM_INT);
        $stmt->bindValue(5, (new DateTime())->format('Y-m-d H:i:s'));

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
    // Método busca no banco empresas na tabela usuarios com seus relacionamentos onde usuarios.id = $idUsuario
    // e pj.bd = $banco retornando o primeiro valor encontrado 
    public function buscaUsuarioEmpresa($idUsuario, $banco)
    {
        $sql = 'SELECT 
                    u.id,
	                u.nome,
	                ul.nivel  
                FROM empresas.usuarios u
                INNER JOIN empresas.pj_usuario_link ul ON ( u.id = ul.usuario_id )
                INNER JOIN empresas.pj pj ON (pj.id = ul.pj_id )
                WHERE 
                        u.id = ? 
                    AND pj.bd = ?';
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $idUsuario, \PDO::PARAM_INT);
        $stmt->bindValue(2, $banco);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function atualizaAssinatura($dados)
    {
        $sql = 'UPDATE empresas.usuarios 
                SET 
                    assinatura = ? 
                WHERE 
                    id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $dados['assinatura']);
        $stmt->bindValue(2, $dados['usuario_id'], \PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function buscaPorID($id)
    {

        $sql = 'SELECT
                    * 
                FROM empresas.usuarios 
                WHERE id = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function buscaUsuarioDeEmpresaPorNivel($banco, $nivel)
    {
        $SITUACAO_ATIVO = 1;
        $sql = 'SELECT
                    u.id, 
                    u.nome,
                    pul.nivel,
                    p.nome_fantasia 
                FROM empresas.usuarios u 
                INNER JOIN empresas.pj_usuario_link pul ON (u.id = pul.usuario_id )
                INNER JOIN empresas.pj p ON (pul.pj_id = p.id )
                WHERE 
                    p.bd = ? 
                    AND pul.nivel = ? 
                    AND pul.situacao = ?
                ORDER BY u.nome ';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $banco);
        $stmt->bindValue(2, $nivel, \PDO::PARAM_INT);
        $stmt->bindValue(3, $SITUACAO_ATIVO, \PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function salvaIdRecuperaSenha($id, $idUsuario)
    {
        $sql = 'INSERT INTO recuperacao_senha 
                            (
                                id, 
                                id_usuario
                            )
                VALUES (?,?); ';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $idUsuario, \PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function buscaIdRecuperaSenha($id)
    {
        $sql = 'SELECT 
                    *
                FROM recuperacao_senha 
                WHERE 
                    id = ? ';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function atualizaSenha($idUsuario, $senha)
    {
        $sql = 'UPDATE empresas.usuarios 
                SET 
                    senha = ?
                WHERE 
                    id = ? ';
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, sha1($senha));
        $stmt->bindValue(2, $idUsuario, \PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function buscaBancoPorApelidoBanco($apelido)
    {
        $sql = 'SELECT 
                    bd,
                    nome_fantasia,
                    razao_social,
                    assinatura_proprietario,
                    bd,
                    email_documentos
                FROM pj
                WHERE
                    apelido_bd = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $apelido);

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }

    public function verificaUsuarioPorId($id, $senha)
    {
        $sql = 'SELECT 
                    id,
                    nome,
                    assinatura
                FROM usuarios
                WHERE
                    id = ?
                    AND senha = ?';

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->bindValue(2, sha1($senha));

        try {
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            new Exceptions($e);
        }
    }
}
