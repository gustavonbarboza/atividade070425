<?php

function db_connect()
{
    static $connection = null;
    $config = array();
    $config_path = dirname(__FILE__) . '/../config/config.php';

    if ($connection !== null) {
        return $connection;
    }

    if (!file_exists($config_path)) {
        die('Arquivo de configuração não encontrado em config/config.php.');
    }

    $config = include $config_path;

    $connection_string = sprintf(
        'host=%s port=%s dbname=%s user=%s password=%s',
        $config['db_host'],
        $config['db_port'],
        $config['db_name'],
        $config['db_user'],
        $config['db_password']
    );

    $connection = @pg_connect($connection_string);

    if (!$connection) {
        die('Não foi possível conectar ao PostgreSQL. Revise as credenciais em config/config.php e confirme se a extensão pgsql está habilitada no PHP.');
    }

    pg_set_client_encoding($connection, 'UTF8');

    return $connection;
}

