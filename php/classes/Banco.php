<?php

namespace classes;

class Banco
{
  function __construct($base_dados = false, $tabela = false, $senha = false, $login = false)
  {
      $this->data_base = $base_dados;
      $this->tabela = $tabela;
      $this->senha = $senha;
      $this->login = $login;
  }

  public function create_banco()
  {
    $base = $this->data_base;

    $sql = mysqli_connect('localhost','root','');
    if ($sql->connect_error)
    {
    die("Falha na conexao: " . $sql->connect_error);
    }

    $data = $sql->query("SHOW DATABASES");
    while ($result = $data->fetch_row()) {
        $result[0] == $base ? $bool_base = true : $bool_base = false;
    }

    if($bool_base){
      $sql->query("DROP DATABASE".$base);
      $sql->query("CREATE DATABASE ".$base);
    }else{
      $sql->query("CREATE DATABASE".$base);
    }
      $sql->close();
  }

  public function conectar(){
    
  }


}
