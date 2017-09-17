<?php

/**
* Antes de iniciar o exercicio, execute o programa e tente compreender a logica do codigo abaixo
**/
class Principal
{
    public static function main(array $config)
    {
        $climate = new \League\CLImate\CLImate;

        $database = $config['default_schema'];
        $servername = $config['servername'];
        $username = $config['username'];
        $password = $config['password'];

        /* Conectando ao Servidor de Banco de Dados */
        try {
            $conn = new PDO("mysql:host=$servername;", $username, $password);

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // $conn->query("use $database");
        } catch (PDOException $e) {
            $climate->error($e->getMessage());
            exit;
        }

        $climate->info("\nBD Conectado!\n");

        try {
            /* Criando Base de Dados */

            /**
             * @var {PDOStatement}
             */
            $rs = $conn->query('SHOW DATABASES'); // Seleciona todas as bases de dados do servidor

            // para todosos databases
            while ($row = $rs->fetch(\PDO::FETCH_NUM)) {
                // se a base de dados 'teste' já existir
                if ($database === $row[0]) {
                    $conn->exec('DROP DATABASE `'.$database.'`');

                    $climate->shout("\nAntigo database dropado!\n");

                    break;
                } // deleta $database existente
            }

            unset($row);

            $conn->exec('CREATE DATABASE `'.$database.'`'); // cria uma nova $database com o mesmo nome
            $conn->exec('USE `'.$database.'`');

            $climate->info('Database criada!');
        } catch (\Exception $e) {
            $climate->error('Ocorreu um erro ao criar a base de dados!'.PHP_EOL);
            $climate->error($e->getMessage());
        }

        try {
            // NOTE: não há necessidade de fechar a conexão no PHP, uma vez que ela é genérica

            /* Criando Tabela */
            $query_string = <<<SQL
CREATE TABLE Alunos (
    nome varchar(50),
    curso varchar(50),
    fase int,
    cpf varchar(50) PRIMARY KEY
)
SQL;

            $conn->query($query_string); // executa

            $climate->info("\nTabela 'Alunos' Criada!\n\n");
            /*****************************************/

            $query_string = ''; // limpando variável para receber novo valor

            /*Inserindo $valores*/
            $nome  = "Maria";
            $curso = "Informatica";
            $cpf   = "123.456.789-10";

            // Tabela a ser analisada
            $tabela  = 'Alunos';
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

            $climate->out($query_string); // 'DEBUG'

            if ($conn->exec($query_string)) { // executa
                $climate->out("Dados Inseridos!");
            } else {
                $climate->error("Nenhum dado inseridos!");
            }
            /*****************************************/

            // $query_string = ''; // limpando variável para receber novo valor

            /* Pesquisando um determinado valor */
            $query_string = "SELECT * from alunos";

            $rs = $conn->query($query_string, \PDO::FETCH_ASSOC); // criando ponteiro para a tabela

            $table = [];

            foreach ($rs as $key => $row) {
                $table[] = $row;
            }

            $climate->table($table);

            /**********************************/
        } catch (\Exception $e) {
            // exceção geral. Crie mais catchs do tipo SQLException etc para capturar exceções mais eficazmente
            $climate->backgroundRed()->white()->error("\n\tOcorreu um Erro!\t\n");
            $climate->error($e->getMessage());
            exit;
        }
    }
}
