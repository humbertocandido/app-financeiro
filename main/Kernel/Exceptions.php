<?php

namespace ODC\Kernel;

class  Exceptions extends \Exception
{
    public function __construct(\Exception $erro, $autenticado = true)
    {
        $accept = explode(',', $_SERVER['HTTP_ACCEPT']);
        if (in_array('application/json', $accept)) {
            header('HTTP/1.1 404');

            if (!$autenticado) {
                header('HTTP/1.1 401');
            }

            header("Content-type: application/json");
            echo json_encode([
                "erro" => $erro->getMessage(),
            ]);
            die;
        }

        echo "<pre>";
        print_r($erro);
        die;
    }
}
