<?php

namespace ODC\Filtros;

use Exception;
use ODC\Kernel\Exceptions;
use ODC\Modelos\UsuarioEmpresa;

/* Essa classe serve como filtro para checar se um usuário está autenticado
o usuário está autenticado sempre que a variável global de sessão estiver com 
o índice logado = true, tiver um nome de banco configurado e também um usuário  */

class Autenticacao
{
    // Método checa se a sessão tem os requisitos necessários para o usuário estar logado
    public static function checarAutenticado()
    {
        $usuario = self::validaUsuario();
        $logado = self::validaLogado();
        $banco = self::validaBanco();

        if (!$usuario && !$logado && !$banco) {
            self::validaRequisicao();
            setcookie(session_name(), '', 1);
            if (session_status() === PHP_SESSION_NONE) {
                session_destroy();
            }
            header('Location: ' . URLSISTEMA . '/login');
            die;
        }
    }
    // Método válida se existe um usuário na sessão 
    private static function validaUsuario()
    {
        if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
            return true;
        }

        return false;
    }
    // Método checa se existe um índice logado e se ele é true
    private static function validaLogado()
    {
        if (isset($_SESSION['logado']) && $_SESSION['logado']) {
            return true;
        }

        return false;
    }
    // Método checa se existe um índice banco e se ele não está vazio
    private static function validaBanco()
    {
        if (isset($_SESSION['banco']) && !empty($_SESSION['banco'])) {
            return true;
        }

        return false;
    }
    // Método que retorna o usuário existente na sessão 
    public static function usuario()
    {
        if (self::validaUsuario()) {
            return $_SESSION['usuario'];
        }
    }
    // Método vai retornar o banco armazenado na sessão
    public static function banco()
    {
        if (self::validaBanco()) {
            return $_SESSION['banco'];
        }
    }
    // Método vai retornar a empresa armazenado na sessão
    public static function empresa()
    {
        if (self::validaBanco()) {
            return $_SESSION['empresa'];
        }
    }

    private static function validaRequisicao()
    {
        $accept = explode(',', $_SERVER['HTTP_ACCEPT']);

        if (in_array('application/json', $accept)) {
            header('HTTP/1.1 401');
            header("Content-type: application/json");
            echo json_encode([
                "msg" => "Acesso não autorizado",
                "redireciona" => URLSISTEMA . "/login"
            ]);
            die;
        }
    }

    public static function verificaLoginPorId($id, $senha)
    {
        $logado = (new UsuarioEmpresa)->verificaPorId($id, $senha);

        if (!$logado) {
            throw new Exceptions(new Exception("Erro de autenticação"), false);
        }

        return true;
    }
}
