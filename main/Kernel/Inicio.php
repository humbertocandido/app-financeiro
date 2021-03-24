<?php

namespace ODC\Kernel;

ini_set('session.cookie_httponly', 1);
/*Classe principal do sistema, todas as requisições do servidor são enviadas a essa classe
onde cada requisição será redirecionada para a respectiva classe com base na url, se 
os parâmetros da url não encontrarem uma classe será mostrada uma página de erro 

Por padrão o controle principal tem o nome de Home sendo o método padrão o index para 
qualquer controle criado 
*/

require_once __DIR__ . "/../config.php";

class Inicio
{
    // Controle padrão caso nenhum seja passado na URL
    protected $controle = "HomeControle";
    // Método padrão caso não seja passado na url
    protected $metodo = "index";
    // Array de parâmetros 
    protected $params = [];
    // Ao iniciar uma requisição o construtor é chamado e feita as configurações e chamado o Controle correspondente
    public function __construct()
    {
        $this->iniciaSessao();
        $url = $this->getUrl();
        $this->chamaControle($url);
    }
    // Pegando o valor da url que veio na super global $_GET 
    protected function getUrl()
    {
        return $_GET['url'] ?? null;
    }
    /* Esse método recebe a url que foi passada como parâmetro, se não vier vazia ou nula os valores são 
desmembrados em um array onde são tiradas as duas primeiras posições que são o valor vazio 
e o nome da pasta do projeto */
    protected function chamaControle($url)
    {
        $caminho = [];

        if ($url != null) {
            $caminho = explode('/', $url);
            // tira o valor vazio do array
            // array_shift($caminho);
            // tira o nome da pasta do projeto
            // array_shift($caminho);
            // manda checar se a posição que sobrou corresponde a alguma classe de controle

            $this->confControle($caminho[0]);
            // tira a posição correspondente a um controle
            array_shift($caminho);
            // manda checar se a posição que sobrou corresponde a um método no controle caso exista
            $this->confMetodo($caminho[0] ?? null);
            // tira a posição correspondente ao metódo do array
            array_shift($caminho);
        }

        // Verifico se é uma requisição GET ou POST e pego seus respectivos parâmetros de acordo com o verbo HTTP
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->params = $_POST;
        } else {
            $this->params = $caminho;
        }
        // Pego o nome do controle 
        $controle = $this->pegaControlador();
        // Pego o nome do método 
        $metodo = $this->metodo;
        // Crio uma instância do controle e chamo o método correspondente passando os parâmetros caso existam
        (new $controle())->$metodo($this->params);
    }
    /* Método onde verificamos se existe o controle chamado pela url ou lançamos um exceção caso não exista */
    protected function confControle($caminho)
    {
        $controle = ucfirst($this->formataNome($caminho));

        if (file_exists(__DIR__ . "/../Controladores/" . $controle . "Controle.php")) {
            $this->controle = $controle . "Controle";
        } else {
            throw new \Exception("Controle não encontrado", 1);
            exit();
        }
    }
    /* Método onde verificamos se existe o método do controle chamado pela url ou lançamos um exceção caso não exista */
    protected function confMetodo($metodo)
    {
        $metodo = $this->formataNome($metodo);

        if (method_exists($this->pegaControlador(), $metodo)) {
            $this->metodo = $metodo;
        } else {
            if ($metodo != null) {
                throw new \Exception("Método não existe", 1);
                exit();
            }
        }
    }
    // Método que retorna o nome completo do controle caso ele exista
    protected function pegaControlador()
    {
        return "ODC\Controladores\\" . $this->controle;
    }
    // Método que inicia a sessão para todas as requisições e renova o tempo do cookie da sessão caso já exista
    private function iniciaSessao()
    {
        session_name("odcsys");
        session_start();
        // Verifico se já existe um Cookie com chave PHPSESSID
        if (isset($_COOKIE[session_name()])) {

            // Se o cookie tem o mesmo id da sessão iniciada renovo o tempo de expiração 
            $velha = $_COOKIE[session_name()];

            if ($_COOKIE[session_name()] == session_id()) {
                setcookie(
                    session_name(),
                    session_id(),
                    (time() + 1800)
                );
            } else {
                setcookie(session_name(), $velha, 1);
                setcookie(session_name(), session_id(), (time() + 1800));
            }
        } else {
            // Se não existe configuro um Cookie sendo a chave o nome de sessão padrão 
            // e id da sessão que foi dado start e o tempo de expiração do cookie para 30 minutos
            setcookie(session_name(), session_id(), (time() + 1800));
        }
    }

    private function formataNome($string)
    {
        $palavras = explode('-', $string);
        $nomeMetodo = '';
        foreach ($palavras as $chave => $valor) {
            $nomeMetodo .= $chave == 0 ? $valor : ucfirst($valor);
        }

        return $nomeMetodo;
    }
}
