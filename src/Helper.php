<?php

/**
* Antes de iniciar o exercicio, execute o programa e tente compreender a logica do codigo abaixo
**/
class Helper
{
    /**
     * @property {\League\CLImate\CLImate} cli : The CLI input/output manager class.
     */
    private $cli = null;

    /**
     * Just a little lazy.
     * @return {\League\CLImate\CLImate} A CLI manager class.
     */
    public function getCLI() : \League\CLImate\CLImate
    {
        return $this->cli ?? new \League\CLImate\CLImate;
    }

    public function connect_database(array $config)
    {
        $database = $config['default_schema'];
        $servername = $config['servername'];
        $username = $config['username'];
        $password = $config['password'];

        /* Conectando ao Servidor de Banco de Dados */
        try {
            $conn = new \PDO("mysql:host=$servername;", $username, $password);

            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // $conn->query("use $database");
        } catch (\PDOException $e) {
            $this->cli->error($e->getMessage());
            exit;
        }

        $this->cli->info("\nBD Conectado!\n");
    }

    /**
     *
     */
    public function drop_database(string $database)
    {
        try {
            $conn->exec('DROP DATABASE IF EXISTS `'.$database.'`');

            $this->cli->shout("\nAntigo database dropado!\n");
        } catch (\Exception $e) {
            $this->cli->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
            $this->cli->error($e->getMessage());
        }
    }

    public function create_database(string $database)
    {
        try {
            /* Criando Base de Dados */

            /**
             * @var {PDOStatement}
             */
            $rs = $conn->query('SHOW DATABASES LIKE \''.$database.'\''); // Seleciona todas as bases de dados do servidor

            // se a base de dados 'teste' já existir
            if ($rs->rowCount() > 0) {
                $conn->exec('DROP DATABASE `'.$database.'`');

                $this->cli->shout("\nAntigo database dropado!\n");
            } // deleta $database existente

            $conn->exec('CREATE DATABASE `'.$database.'`'); // cria uma nova $database com o mesmo nome
            $conn->exec('USE `'.$database.'`');

            $this->cli->info('Database criada!');
        } catch (\Exception $e) {
            $this->cli->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
            $this->cli->error($e->getMessage());
        }
    }
}
