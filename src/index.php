<?php

declare(strict_types=1);

require '../vendor/autoload.php';

$climate = new League\CLImate\CLImate;

$climate->backgroundBlue()->white()->out("\r\n\tBem vindo bixo loco!\t\r\n");


$input = $climate->confirm("Quer executar o script Principal?");

// Continue? [y/n]
if (!$input->confirmed()) {
    $options = [
        'connect_database' => 'Conexão ao Banco de Dados.',
        // $climate->out("\t2. Criação de uma nova DATABASE.");
        'create_database' => 'Criar uma base de dados.',
        // $climate->out("\t3. Conexão à uma DATABASE.");
        'use_database' => 'Escolher uma base de dados.',
        // $climate->out("\t4. Criação de Tabelas em uma DATABASE.");
        'create_table' => 'Criar tabela na base de dados.',
        // $climate->out("\t5. Inserção de informações numa tabela.");
        'insert_into' => 'Inserir info. em uma tabela.',
        // $climate->out("\t6. Busca de Informações numa tabela'.");
        'fetch' => 'Buscar dados de uma tabela.',

        // aluno
        'delete_aluno' => 'Deletar Aluno. (via CPF)',
        'update_aluno' => 'Modificar a fase de um aluno.', // e imprimir em tela os dados anteriores e posteriores a modificação
    ];

    $input = new \Menu($options);
    // $input = $climate->checkbox('O que vamos fazer hoje?', $options);

    $response = $input->prompt();

    $climate->shout($response);

    \MenuActions::{$response}();

} else {
    $config = json_decode(file_get_contents('config.txt'), true);

    \Principal::main($config['database']);
}
