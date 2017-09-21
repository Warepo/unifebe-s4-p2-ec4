<?php
  /**
   *  Este objeto contrala o ambiente (a camada de cima) do programa
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

    /**
     * [index index site]
     * @return [view] [retorna a vie com a variavel do formulario]
     */
    public function index()
    {
        $formulario = $this->ambiente->varre_tabela('alunos');
        include '../padrao.php';
    }

    public function prepara_insert()
    {
      $tabela = $_POST['tabela'];
      unset($_POST['tabela']);

      $this->ambiente->inserir($tabela,$_POST);
    }

  }
  // print_r($_POST);
  // die;
  $show = new Ambiente();
  $show->{$_GET['funcao']}();
