<?php

namespace ODC\Kernel;

class ModeloBanco
{
    protected function retornaTodosDados($stmt)
    {
        try {
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }

    protected function retornaUnico($stmt)
    {
        try {
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }

    protected function execute($stmt)
    {
        try {
            return $stmt->execute();
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }
}
