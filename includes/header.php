<?php

require_once dirname(__FILE__) . '/functions.php';

if (!isset($page_title)) {
    $page_title = 'Cadastro de Funcionários';
}

if (!isset($page_heading)) {
    $page_heading = $page_title;
}

if (!isset($active_menu)) {
    $active_menu = '';
}

$flash = get_flash();

?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($page_title); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-body">
    <div class="app-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand-icon">&#127760;</span>
                <span class="brand-text">Cadastro de Funcionários</span>
            </div>

            <nav class="menu">
                <a href="funcionario_form.php" class="<?php echo esc(menu_class($active_menu, 'inicio')); ?>">Início</a>
                <a href="funcionarios.php" class="<?php echo esc(menu_class($active_menu, 'listagem')); ?>">Listagem</a>
            </nav>

            <div class="user-box">
                <span>Olá, <?php echo esc(current_user_name()); ?></span>
                <a href="logout.php">Sair</a>
            </div>
        </header>

        <main class="page-card">
            <div class="page-header">
                <h1><?php echo esc($page_heading); ?></h1>
            </div>

            <?php if (!empty($flash)) { ?>
                <div class="flash-message <?php echo esc($flash['type']); ?>">
                    <?php echo esc($flash['message']); ?>
                </div>
            <?php } ?>

