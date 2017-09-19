<?php
/**
 *   Obect 'Banco', cria o ambiente para rodar a aplicação, e as funções DML
 */
namespace classes;

class Banco
{
  function __construct($server = false,$login = false,$senha = false)
  {
    $this->connect_server = $server;
    $this->senha = $senha;
    $this->login = $login;

    $this->sql = mysqli_connect($this->connect_server,$this->login,$this->senha);
  }

/**
  * Cria o ambiente para rodar a aplicação;
  *
  *  @author Anologicon
  *
  */
  public function create_banco()
  {
    //faz conexao com o banco

    // Verifica se ouve algum erro de conexão
    if ($this->sql->connect_error)
    {
    die("Falha na conexao: " . $this->sql->connect_error);
    }

    // Preparando query para mostrar todas as base de dados
    $data = $this->sql->query("SHOW DATABASES");
    /*
    Para cada base de dados comparamos com a base 'default' da aplicação
    Se for igual ele retorna true setando $bool_base
    */
    while ($result = $data->fetch_row())
     {
        // $result[0] == 'teste' ? $bool_base = true : $bool_base = false;
        $bool_base = $result[0] == 'teste';
    }
    /*
    Se $bool_base estiver true ele apaga a base de dados atual e cria uma
    nova 'teste'
    Se $bool_base estiver false ele cria uma base nova 'teste'
    */
    if($bool_base){
      $this->sql->query("DROP DATABASE teste");
      $this->sql->query("CREATE DATABASE  teste");
    }else{
      $this->sql->query("CREATE DATABASE teste");
    }
    $this->sql->close();
  }

  /**
   * [conectar cria conexão com deafault padrão caso não receba o primeiro parametro]
   * @param  [String] $_server [caminho do servidor]
   * @param  [String] $_log    [login para o servidor]
   * @param  [String] $_pass   [senha]
   * @param  [String] $_base   [a base de dados]
   * @return           [cria a conexão de dados ou usa o deafult]
   */
  public function conectar($_server = null, $_log = null, $_pass = null,$_base = null)
  {
    //se $_server é null, cria uma conexão default, se não ele cria com os parametros
    if($_server == false){
      $this->sql = mysqli_connect($this->connect_server,$this->login,$this->senha,'teste');
    }else {
      $this->sql = mysqli_connect($_server,$_log,$_pass,$_base);
    }
  }

  /**
   * [criar_tabela cria a tabela]
   * @param  [String] $_tabela [nome da tabela que deseja criar]
   *
   */
  public function criar_tabela($_tabela = null)
  {
    // se a $_tabela for null e se ela não existir na base de dados, cria como default
    //  tabela alunos e insere dados se não ele cria a tabela com o parametro que foi passado
    if($_tabela == false){
      if($this->sql->query('CREATE TABLE IF NOT EXISTS Alunos (nome varchar(50),curso varchar(50),fase int,cpf varchar(50) PRIMARY KEY)')){
            $this->sql->query('INSERT INTO alunos VALUES ("Maria","Informatica",null,"123.456.789-10")');
      }
    }else{
      $this->sql->query('CRETE TABLE IF NOT EXISTS '.$_tabela);
    }
  }

  /**
   * [inserir monta um insert para a tabela $_tabela]
   * @param  [String] $_tabela  [tabela que sera inserido]
   * @param  [Array] $_valores [Array Associativa com chaves e valores]
   */
  public function inserir($_tabela,$_valores)
  {
    $chave = ""; //limpando variavel
    $valor = "";//limpando variavel
    //para cada chave valor vinda em uma array associativa $_valores
    //monta um insert
    foreach ($_valores as $key => $value) {
      $chave .= $key.","; //montando a variavel $key {chave} colocando em uma string com ','
      $valor  .= "'".$value."'".",";//montando a variavel $value {valor} colocando em uma string com ',', colocando aspas simples nos valores
    }
    $chave = rtrim($chave,","); //retirando a ultima virgula
    $valor = rtrim($valor,",");// retirando a ultima virgula
    if(!$this->sql->query("INSERT INTO $_tabela ($chave) VALUES ($valor)")){
      die($this->sql->error);
    }
    //fazendo o insert se der errado o script morre mosntrando o erro;
  }

  /**
   * [varre_tabela varre a tabela trazendo as colunas]
   * @param  [String] $_tabela [nome da tabela]
   * @return [object]          [as colunas das tabelas]
   */
  public function varre_tabela($_tabela)
  {
    $colunas = $this->sql->query('DESCRIBE '.$_tabela);
    return $colunas;
  }


}
