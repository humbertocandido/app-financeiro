<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../main/config.php";

use ODC\Kernel\Inicio;

new Inicio;
