<?php

namespace ODC\Kernel;

use ODC\Filtros\Autenticacao;
use Dompdf\Dompdf;
use Exception;
use ODC\Modelos\Produtos;

/*Esse é o controlador padrão, todos os controles do sistema terão que herdar dessa classe para a renderização das views*/

abstract class Controle
{
    /*Esse método renderiza uma página caso exista, também envia dados para a página que será renderizada
    e os links dos javascripts necessários para o funcionamento correto da página*/
    public function renderizar($caminho, $dados = array(), $scriptsPagina = array(), $css = array())
    {
        // javascripts que são necessários em todas as páginas do sistema
        $scriptsPadroes = [
            URLASSESTS . "/vendors/base/vendor.bundle.base.js",
            URLASSESTS . "/js/off-canvas.js",
            URLASSESTS . "/js/hoverable-collapse.js",
            URLASSESTS . "/js/template.js",
            URLASSESTS . "/js/sweetalert2.js",
            URLASSESTS . "/js/logout.js",
            // "/goiania/public/js/jquery-3.4.1.min.js",
        ];

        // retornando o usuario da sessão e colocando dentro do array de dados que vai para a página
        $dados['usuario'] = Autenticacao::usuario();
        $dados['empresa'] = Autenticacao::empresa();

        // concateno os javascripts padrões com os requisitados pela página que será renderizada
        $scripts = array_merge($scriptsPadroes, $scriptsPagina);
        // se o arquivo buscado existir é renderizado
        if (file_exists(__DIR__ . "/../Paginas/" . $caminho . ".php")) {
            require_once __DIR__ . "/../Paginas/layout/layout.php";
        } else {
            // se o arquivo buscado não existir é lançada uma exceção
            throw new \Exception("Página não criada", 1);
        }
    }
    // Método para renderizar páginas que não precisam estar dentro do layout padrão  
    public function renderizarSemLayout($caminho, $dados = [])
    {
        // se o arquivo buscado existir é renderizado
        if (file_exists(__DIR__ . "/../Paginas/" . $caminho . ".php")) {
            require_once __DIR__ . "/../Paginas/" . $caminho . ".php";
        } else {
            // se o arquivo buscado não existir é lançada uma exceção
            throw new \Exception("Página não criada", 1);
        }
        die;
    }

    // Método responsável por responder requisições que esperam uma resposta json
    public function json($dados = null)
    {
        header("Content-type: application/json");
        echo json_encode($dados);
        die;
    }
    // Método responsável por transformar uma string serializado em uma array de de dados
    public function desserializar($string)
    {
        parse_str($string, $dados);
        return $dados;
    }
    // Método responsável por gerar um relatório em pdf de acordo com o caminho do arquivo
    // e os dados passados 
    public function relatorioPDF($caminho, $dados = null)
    {
        ob_start();
        try {
            require_once __DIR__ . "/../Pdf/" . $caminho . ".php";
        } catch (\Exception $e) {
            new Exceptions($e);
        }
        $pdf = ob_get_clean();
        // echo $pdf;
        // die;
        $dompdf = new Dompdf(["enable_remote" => true]);

        $dompdf->loadHtml($pdf);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("file.pdf", ["Attachment" => false]);
    }

    // Método reencaminha para a url recebida como parâmetro 
    public function reencaminhar($url)
    {
        header('Location: ' . $url);
        die;
    }
}
