<?php
require (__DIR__.DIRECTORY_SEPARATOR.'autoload.php');

use classes\Banco;

$ambiente = new Banco('localhost','root','');
$ambiente->create_banco();
$ambiente->conectar();
