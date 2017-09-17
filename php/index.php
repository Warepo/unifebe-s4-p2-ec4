<?php
require (dirname(__FILE__).DIRECTORY_SEPARATOR.'autoload.php');

use classes\Conexao;

$conectar = new Conexao();

$conectar->conectar();
