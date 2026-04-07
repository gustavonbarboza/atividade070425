<?php

if (session_id() === '') {
    session_start();
}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit;
}

function is_post()
{
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}

function esc($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function set_flash($type, $message)
{
    $_SESSION['flash'] = array(
        'type' => $type,
        'message' => $message
    );
}

function get_flash()
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    return null;
}

function is_logged_in()
{
    return !empty($_SESSION['usuario_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        redirect_to('index.php');
    }
}

function current_user_name()
{
    if (!empty($_SESSION['usuario_nome'])) {
        return $_SESSION['usuario_nome'];
    }

    return 'Admin';
}

function current_user_login()
{
    if (!empty($_SESSION['usuario_login'])) {
        return $_SESSION['usuario_login'];
    }

    return 'admin';
}

function get_cargos()
{
    return array(
        'Administrador',
        'Gerente',
        'Assistente',
        'Analista',
        'Coordenador',
        'Financeiro',
        'Recursos Humanos'
    );
}

function status_label($status)
{
    if ($status === 'I') {
        return 'Inativo';
    }

    return 'Ativo';
}

function status_class($status)
{
    if ($status === 'I') {
        return 'status-inativo';
    }

    return 'status-ativo';
}

function menu_class($active_menu, $current_menu)
{
    if ($active_menu === $current_menu) {
        return 'is-active';
    }

    return '';
}

function page_link($script, $params)
{
    if (empty($params)) {
        return $script;
    }

    return $script . '?' . http_build_query($params);
}

function preserve_query_with_page($page)
{
    $params = $_GET;
    $params['page'] = $page;

    return 'funcionarios.php?' . http_build_query($params);
}

function password_matches($plain_password, $stored_password)
{
    if ((string) $stored_password === (string) $plain_password) {
        return true;
    }

    if ((string) $stored_password === sha1($plain_password)) {
        return true;
    }

    if ((string) $stored_password === md5($plain_password)) {
        return true;
    }

    if (function_exists('password_verify') && preg_match('/^\$2y\$|^\$2a\$|^\$argon2/', $stored_password)) {
        return password_verify($plain_password, $stored_password);
    }

    return false;
}
