<?php

namespace ODC\Utilidades;

class FmtStrings
{
    public function __construct()
    {
    }

    public static function removeMascaraTelefone($numero)
    {
        return preg_replace("/\D/", '', $numero);
    }
}
