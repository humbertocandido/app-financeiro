<?php

namespace ODC\Controladores;

use ODC\Filtros\Autenticacao;
use ODC\Kernel\Controle;

class CadastroTesteControle extends Controle
{
    private $js = [
      URLASSESTS . "/js/cadastro_teste/index.js",
    ];

    public function __construct(){
      // Autenticacao::checarAutenticado();
    }

    public function cadastro()
    {
      echo "Chamando um método";
    }

    public function mostrarPaginaEmLayout() {
      $dado['nome'] = "Exemplo de nome indo para a página";
      $this->renderizar("cadastro_teste/dentro_layout", $dado, $this->js);
    }

    public function retornaJson()
    {
      $this->json([
        "nome" => "Novo Programador"
      ]);
    }

    public function mostrarPaginaForaLayout()
    {
      $dados['nome'] = "Exibindo dados em página fora de layout";
      $this->renderizarSemLayout("cadastro_teste/fora_layout", $dados);
    }
}