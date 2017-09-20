<?php

// namespace

class MenuActions
{
    /**
     * @property {\League\CLImate\CLImate} cli : The CLI input/output manager class.
     */
    private $cli = null;
    private $conn;
    private $config;

    /**
     * Just a little lazy.
     */
    private function get_conn()
    {
        return $this->conn ?? $this->connect_database($this->get_config());
    }

    /**
     * Just a little lazy.
     */
    private function get_config()
    {
        return $this->config ?? $this->ask_config();
    }

    /**
     * Just a little lazy.
     * @return {\League\CLImate\CLImate} A CLI manager class.
     */
    public function get_cli() : \League\CLImate\CLImate
    {
        return $this->cli ?? new \League\CLImate\CLImate;
    }

    /**
     * Asks for user input configuration.
     */
    private function ask_config()
    {
        $this->get_cli()->out("\n\nInsira os dados necessários para configuração com o servidor da(s) base de dados:\n");

        $servername = $this->get_cli()->input('Insira a URL de conexão com o servidor: ')->prompt();
        $username = $this->get_cli()->input('Nome de usuário para '.$servername.': ')->prompt();
        $password = $this->get_cli()->input('Palavra-passe para '.$username.': ')->prompt();
        // $default_schemma = $this->get_cli()->input('Escolha o banco de dados inicial em \''.$servername.'\': ')->prompt();

        return $this->config = [
            // 'default_schema' => $default_schemma,
            'servername' => $servername,
            'username' => $username,
            'password' => $password,
        ];
    }



    public function connect_database()
    {
        $config = $this->get_config();

        /* Conectando ao Servidor de Banco de Dados */
        try {
            $this->conn = new \PDO("mysql:host={$config['servername']};", $config['username'], $config['password']);

            // set the PDO error mode to exception
            $this->get_conn()->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // solicita que o usuário escolha um banco de dados
            $this->use_database();

        } catch (\PDOException $e) {
            $this->get_cli()->error($e->getMessage());
            exit;
        }

        $this->get_cli()->info("\nBD Conectado!\n");

        return $this->conn;
    }

    /**
    *
    */
    public function drop_database()
    {
        $config = $this->get_config();

        $climate = $this->get_cli();

        $database = $climate->input("\nNome do BD a dropar: ")->prompt();

        try {
            $this->get_conn()->exec('DROP DATABASE IF EXISTS `'.$database.'`');

            $this->get_cli()->shout("\nAntigo database dropado!\n");
        } catch (\Exception $e) {
            $this->get_cli()->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
            $this->get_cli()->error($e->getMessage());
        }
    }

    public function create_database()
    {
        $config = $this->get_config();

        $climate = $this->get_cli();

        $database = $climate->input("\nNome para a nova BD: ")->prompt();

        try {
            /* Criando Base de Dados */

            /**
            * @var {PDOStatement}
            */
            $rs = $this->get_conn()->query('SHOW DATABASES LIKE \''.$database.'\''); // Seleciona todas as bases de dados do servidor

            // se a base de dados 'teste' já existir
            if ($rs->rowCount() > 0) {
                $this->get_conn()->exec('DROP DATABASE `'.$database.'`');

                $this->get_cli()->shout("\nAntigo database dropado!\n");
            } // deleta $database existente

            $this->get_conn()->exec('CREATE DATABASE `'.$database.'`'); // cria uma nova $database com o mesmo nome
            $this->get_conn()->exec('USE `'.$database.'`');

            $this->get_cli()->info('Database criada!');
        } catch (\Exception $e) {
            $this->get_cli()->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
            $this->get_cli()->error($e->getMessage());
        }
    }

    public function use_database()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        $database = $climate->input("\nNome do database a ser utilizada: ")->prompt();

        $rs = $conn->query('SHOW DATABASES LIKE \''.$database.'\''); // Seleciona todas as bases de dados do servidor

        // se a base de dados 'teste' já existir
        if ($rs->rowCount() > 0) {
            try {
                $conn->exec('USE `'.$database.'`');

                $this->get_cli()->info('Database selecionada!');
            } catch (\Exception $e) {
                $conn->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
                $conn->error($e->getMessage());
            }
        } else {
            $conn->info('Base de dados inexistente!');
        }
    }

    public function create_table()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        // Tabela a ser analisada
        $tabela  = $climate->input('Nome da tabela a ser criada: ')->prompt();

        $qty_fields  = $climate->input('Quantidade de campos da tabela: ')->prompt();

        $fields = [];

        for ($i = 0; $i < $qty_fields; $i++) {
            $fields[$i] = $climate->input('Nome do campo '.($i+1).': ')->prompt().' ';
            $fields[$i] .= $climate->input('Tipo do campo '.$fields[$i].': ')->prompt().',';
        }

        $query_string = 'CREATE TABLE `'.$tabela.'` ('.rtrim(implode($fields), ',').')';

        print_r($query_string);

        try{
            $conn->exec($query_string);
            $climate->out("Tabela criada!");
        } catch(\Exeption $e) {
            $climate->error("Não foi possível criar a tabela!");
            $climate->error($e->getMessage());
        }
    }

    public function insert_into()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        // Tabela a ser analisada
        $tabela  = $climate->input('Tabela para inserir dados: ')->prompt();
        $table_fields = $conn->query('DESCRIBE '.$tabela)->fetchAll(\PDO::FETCH_COLUMN); // pega os campos da tabela

        $valores = "";
        $campos  = "";

        // retorna o numero total de colunas
        $climate->out('Total de Colunas: '.count($table_fields));

        // loop para recuperar os metadados de cada coluna (nome da coluna, tipo do campo, etc)
        foreach ($table_fields as $key => $value) {
            $input = $climate->input('Insira o dado para a coluna "'.$value.'": ');

            // não faz diferença se for INT ou não, o DB é tipado e irá converter o valor
            $valores .= "'" . $input->prompt() ."',"; // concatena somente aspas simples, sem virgula
            $campos  .= $value.','; // recupera nome da coluna
        }

        $query_string = 'INSERT INTO '.$tabela.' ('.rtrim($campos, ',').') VALUES ('.rtrim($valores, ',').')'; // INSERT

        $climate->out($query_string.PHP_EOL); // 'DEBUG'

        if ($conn->exec($query_string)) { // executa
            $climate->out("Dados Inseridos!");
        } else {
            $climate->error("Nenhum dado inseridos!");
        }
    }

    public function fetch()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        // Tabela a ser analisada
        $tabela  = $climate->input('Tabela para buscar dados: ')->prompt();

        /* Pesquisando um determinado valor */
        $query_string = "SELECT * from $tabela";

        $rs = $conn->query($query_string, \PDO::FETCH_ASSOC); // criando ponteiro para a tabela

        $table = [];

        foreach ($rs as $key => $row) {
            $table[] = $row;
        }

        $climate->table($table);
    }

    public function delete_aluno()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        // Tabela a ser analisada
        $cpf  = $climate->input('Digite o CPF do aluno: ')->prompt();

        $query_string = 'DELETE FROM alunos WHERE cpf LIKE \''.$cpf.'\''; // INSERT

        if ($conn->exec($query_string)) { // executa
            $climate->out("Aluno deletado!");
        } else {
            $climate->error("Nenhum aluno deletado!");
        }
    }

    public function update_aluno()
    {
        $climate = $this->get_cli();
        $conn = $this->get_conn();

        // Tabela a ser analisada
        $cpf  = $climate->input('Digite o CPF do aluno: ')->prompt();

        $rs = $conn->query('SELECT nome, cpf FROM alunos WHERE cpf LIKE \''.$cpf.'\'');

        if ($rs->rowCount() > 0) {
            $aluno = $rs->fetch(\PDO::FETCH_ASSOC);

            $fase  = $climate->input('Digite a fase para "'.$aluno['nome'].'": ')->prompt();

            $query_string = 'UPDATE alunos SET fase = '.$fase.' WHERE cpf = "'.$aluno['cpf'].'"';

            if ($conn->exec($query_string)) { // executa
                $climate->out("Aluno atualizado!");
            } else {
                $climate->error("Aluno não atualizado!");
            }
        } else {
            $climate->info('Nenhum aluno encontrado!');
        }
    }
}
