<?php

namespace classes;

class Conexao
{
  function __construct()
  {
  }

  public function conectar()
  {
    $conexao = new mysqli('localhost','root','') or die('Não consegui conectar');
    if ($conexao->connect_error) {
    die("Falha na conexao: " . $conexao->connect_error);
    }

    $sql = "CREATE DATABASE teste";
      if ($conexao->query($sql) === TRUE) {
          echo "DATABASE criado com sucesso !";
      } else {
          echo "Erro ao criar data base: " . $conexao->error;
      }
  }
}
