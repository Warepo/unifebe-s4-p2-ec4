<?php
require (__DIR__.DIRECTORY_SEPARATOR.'autoload.php');

use classes\Banco;

$ambiente = new Banco('teste');
$ambiente->create_banco();
