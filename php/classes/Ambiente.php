<?php
  /**
   *
   */
  require ('../autoload.php');
  use classes\Banco;

  class Ambiente
  {

    function __construct()
    {
      $this->ambiente = new Banco('localhost','root','');
      $this->ambiente->create_banco();
      $this->ambiente->conectar();
      $this->ambiente->criar_tabela();
    }

    public function index()
    {
        $formulario = $this->ambiente->varre_tabela('alunos');
        include '../padrao.php';
    }



  }
  $show = new Ambiente();
  $show->{$_GET['funcao']}();
