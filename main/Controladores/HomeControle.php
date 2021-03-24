<?php

namespace ODC\Controladores;

use ODC\Filtros\Autenticacao;
use ODC\Kernel\Controle;

/* Classe de controle da página principal do sistema */

class HomeControle extends Controle
{
    private $jsPadrao = [
        URLASSESTS . "/js/home/index.js",
    ];

    // Verificando se o usuário está autenticado para acessar o sistema
    public function __construct()
    {
        // Autenticacao::checarAutenticado();
    }
    // método que responde a url /goiania mostrando a página inicial do sistema para um usuário autenticado
    public function index()
    {
        // renderiza a página index dentro da pasta home
        $this->renderizar(
            'home/index',
            [],
            $this->jsPadrao,
            [
                URLASSESTS . '/css/home/index.css'
            ]
        );
    }
}
