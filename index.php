<?php

require_once dirname(__FILE__) . '/includes/functions.php';

if (is_logged_in()) {
    redirect_to('dashboard.php');
}

$erro = '';
$usuario = '';

if (is_post()) {
    require_once dirname(__FILE__) . '/includes/db.php';

    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

    if ($usuario === '' || $senha === '') {
        $erro = 'Preencha usuário e senha.';
    } else {
        $conexao = db_connect();
        $resultado = @pg_query_params(
            $conexao,
            'SELECT id, nome, usuario, senha_hash FROM usuarios WHERE LOWER(usuario) = LOWER($1) AND ativo = TRUE LIMIT 1',
            array($usuario)
        );

        if ($resultado && pg_num_rows($resultado) === 1) {
            $dados_usuario = pg_fetch_assoc($resultado);

            if (password_matches($senha, $dados_usuario['senha_hash'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $dados_usuario['id'];
                $_SESSION['usuario_nome'] = $dados_usuario['nome'];
                $_SESSION['usuario_login'] = $dados_usuario['usuario'];

                redirect_to('dashboard.php');
            } else {
                $erro = 'Usuário ou senha inválidos.';
            }
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}

?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cadastro de Funcionários</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <div class="login-title">
            <span class="login-title-icon">&#128100;</span>
            <h1>Cadastro de Funcionários</h1>
        </div>

        <?php if ($erro !== '') { ?>
            <div class="flash-message error">
                <?php echo esc($erro); ?>
            </div>
        <?php } ?>

        <form action="index.php" method="post" class="login-form">
            <div class="input-group">
                <span class="input-icon">&#128100;</span>
                <input type="text" name="usuario" placeholder="Usuário" value="<?php echo esc($usuario); ?>">
            </div>

            <div class="input-group">
                <span class="input-icon">&#128274;</span>
                <input type="password" name="senha" placeholder="Senha">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <div class="login-divider"></div>

        <div class="login-links">
            <a href="forgot_password.php">Esqueci minha senha</a>
        </div>
    </div>
</body>
</html>
