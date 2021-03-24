<?php

namespace ODC\Controladores;

use ODC\Filtros\Autenticacao;
use ODC\Kernel\Controle;
use ODC\Modelos\UsuarioEmpresa;
use ODC\Utilidades\Contatos\Email\APIMailJet;
use ODC\Utilidades\Mailjet;

/* Classe Controle responsável por mostrar página de login, autenticar um usuário informado, 
fazer o login e também fazer o logout de um usuário autenticado */

class LoginControle extends Controle
{
    // Método mostra a página de login quando na url estiver /login
    public function index($dados = [])
    {
        $this->renderizarSemLayout("login/login", $dados);
    }

    // Método responsável por autenticar um usuário se as credenciais passadas existem no banco
    // url = /login/logar POST
    public function logar($dados)
    {
        // Busco por um usuário no banco da cidade informada com as credenciais passadas na requisição
        // $autenticado = (new Usuario())->verifica($dados);
        $autenticados = (new UsuarioEmpresa())->verifica($dados);

        // Se não foi encontrado um usuário retorno uma mensagem de erro e encerro a execução do script
        if (!$autenticados) {
            $this->index(['erro' => 'Usuário e/ou senha inválido(s)']);
            die;
        }

        $this->colocaUsuarioSessao($autenticados[0]);

        if (count($autenticados) > 1) {
            $this->trocar($autenticados, $dados);
        }

        // Se encontramos um usuário com as credenciais passadas, registro ele e o banco na sessão
        $this->configuraBanco($autenticados[0]);
        // Encaminho o usuário para a página principal do sistema
        header("Location: " . URLSISTEMA);
        die;
    }

    // Faço o logout do usuário do sistema pela url = /login/logout 
    public function logout()
    {
        // Coloco o cookie de sessão sem valor, o navegador excluirá ele 
        setcookie(session_name(), '', 1);
        // Destrói os dados da sessão no servidor
        session_unset();
        // Encaminha o usuário para a página de login
        header("Location: " . URLSISTEMA . "/login");
    }

    // Registra os dados na sessão do usuário caso ele tenha credenciais de acesso válidas
    private function configuraBanco($banco)
    {
        $_SESSION['banco'] = $banco['bd'];
        $_SESSION['empresa'] = $banco['nome_fantasia'];
    }

    // Coloca os dados do usuário na sessão aberta
    private function colocaUsuarioSessao($usuario)
    {
        $_SESSION['logado'] = true;

        $_SESSION['usuario']['id'] = $usuario['id'];
        $_SESSION['usuario']['nome'] = $usuario['nome'];
        $_SESSION['usuario']['nivel'] = $usuario['nivel'];
    }
    // Método renderiza a página login/trocar.php com os dados recebidos com parâmetro 
    private function trocar($dados, $usuario)
    {
        $this->renderizarSemLayout('login/trocar', [
            "empresas" => $dados,
            "usuario" => $usuario
        ]);
    }
    // Método responde a url /login/selecionaClinica encaminhado os dados para outro destino
    public function selecionaClinica($dados)
    {
        $idUsuario = Autenticacao::usuario()['id'];
        $conexaoBancoEmpresa = new UsuarioEmpresa();
        $empresa = $conexaoBancoEmpresa->buscaEmpresa($dados['banco']);
        $usuario = $conexaoBancoEmpresa->buscaUsuarioEmpresa($idUsuario, $dados['banco']);

        $this->colocaUsuarioSessao($usuario);
        $this->configuraBanco($empresa);
        $this->json(URLSISTEMA);
    }
    // Método reponde a url /login/trocaClinica renderizando uma página com os dados buscados
    public function trocaClinica()
    {
        Autenticacao::checarAutenticado();
        $usuario = Autenticacao::usuario();
        $empresas = (new UsuarioEmpresa())->empresasUsuario($usuario['id']);
        $this->trocar($empresas, $usuario);
    }
    // Método responde a url /login/registrar renderizando a página login/registrar.php
    public function registro($dados)
    {
        $this->renderizarSemLayout('login/registrar', $dados);
    }
    // Método responde a url /login/recuperar renderizando a página login/recuperar.php
    public function lembrarSenha($request)
    {
        $dados = array();
        if (isset($request[0]) && $request[0] == 'erro') {
            $dados['erro'] = 'E-mail não encontrado!';
        }
        $this->renderizarSemLayout('login/recuperar', $dados);
    }
    // Método responde a url /login/checaEmail retornando um json de sucesso ou um erro   
    public function checaEmail($dados)
    {
        $email = (new UsuarioEmpresa)->temEmail($dados['email']);
        $this->json($email);
    }
    // Método reponde a url /login/registrar envia os dados para a camada de modelo 
    // responde com um uma mensagem de sucesso ou um erro
    public function cadastrar($dados)
    {
        $registro['cadastrado'] = (new UsuarioEmpresa())->salvar($dados);

        if ($registro['cadastrado']) {
            $registro['usuario'] = $dados;
        }

        (new LoginControle)->registro($registro);
    }
    // Método trata url /login/recuperarSenha retornando sucesso no envio do e-mail ou um de e-mail não encontrado
    public function recuperarSenha($request)
    {
        $usuario = (new UsuarioEmpresa)->temEmail($request['email']);

        if (!$usuario) {
            $this->reencaminhar(URLSISTEMA . "/login/lembrar-senha/erro");
        }

        $msg = $this->msgRecuperacao($usuario);

        $enviou = $this->enviaEmail($usuario['email'], $usuario['nome'], "Recuperação de senha", $msg);

        if (!$enviou) {
            die("E-mail não enviado!<br/>Fale com o administrador do sistema");
        }

        $this->reencaminhar(URLSISTEMA);
    }
    // Método para envio de e-mail para recuperação de senha de usuário
    private function enviaEmail($email, $usuario, $assunto, $mensagem)
    {
        $sender = new APIMailJet();
        $sender->setAssunto($assunto);
        $sender->setConteudoHTML($mensagem);
        return $sender->enviar(
            [
                "Email" => 'devodcgyn@gmail.com',
                "Name" => 'Odonto Company'
            ],
            [
                'Email' => $email,
                'Name' => $usuario
            ]
        );
    }

    public function msgRecuperacao($usuario)
    {
        $idRecuperacao = $this->gerarIdRecuperacao();
        $this->salvaIRecuperarSenha($idRecuperacao, $usuario['id']);

        $msg = 'Acesse o link para fazer a alteração de sua senha: 
             ' . DOMINIO
            . URLSISTEMA . '/login/atualizar-senha/'
            . $idRecuperacao;

        return $msg;
    }

    public function atualizarSenha($request)
    {
        $IDRECUPERACAOSENHA = 0;
        $this->renderizarSemLayout('login/trocar_senha', ['id_recupera' => $request[$IDRECUPERACAOSENHA]]);
    }

    // Método gera um Id único para a tabela contrato_adesao_paciente
    private function gerarIdRecuperacao()
    {
        $idRecuperacao = null;

        do {
            $idRecuperacao = uniqid();
        } while ((new UsuarioEmpresa())->buscaIdRecuperaSenha($idRecuperacao));

        return $idRecuperacao;
    }

    private function salvaIRecuperarSenha($idRecuperacaoSenha, $idUsuario)
    {
        (new UsuarioEmpresa())->salvaIdRecuperaSenha($idRecuperacaoSenha, $idUsuario);
    }

    public function trocaSenha($request)
    {
        $conUsuarioEmpresa = new UsuarioEmpresa();
        $idUsuario = $conUsuarioEmpresa->buscaIdRecuperaSenha($request['id_recuperacao']);
        $conUsuarioEmpresa->atualizaSenha($idUsuario['id_usuario'], $request['senha']);
        $this->reencaminhar(URLSISTEMA . '/login');
    }
}
