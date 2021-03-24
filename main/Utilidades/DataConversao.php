<?php

namespace ODC\Utilidades;

class DataConversao
{
    public function __construct()
    {
    }

    public static function converteMes($str)
    {
        $mes = [
            'Jan' => 'Janeiro',
            'Feb' => 'Fevereiro',
            'Mar' => 'Março',
            'Apr' => 'Abril',
            'May' => 'Maio',
            'Jun' => 'Junho',
            'Jul' => 'Julho',
            'Aug' => 'Agosto',
            'Sep' => 'Setembro',
            'Oct' => 'Outubro',
            'Nov' => 'Novembro',
            'Dec' => 'Dezembro',

        ];

        return $mes[$str];
    }

    public static function converteNumeroMes($str)
    {
        $mes = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',

        ];

        return $mes[$str];
    }
}
