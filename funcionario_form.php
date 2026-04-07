<?php

require_once dirname(__FILE__) . '/includes/auth.php';
require_once dirname(__FILE__) . '/includes/db.php';

$conexao = db_connect();
$cargos = get_cargos();
$erros = array();
$funcionario_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$modo_visualizacao = $funcionario_id > 0 && isset($_GET['visualizar']) && $_GET['visualizar'] === '1';
$registro_existente = false;

$funcionario = array(
    'id' => '',
    'nome' => '',
    'cargo' => '',
    'email' => '',
    'telefone' => '',
    'situacao' => 'A'
);

if ($funcionario_id > 0) {
    $resultado = pg_query_params(
        $conexao,
        'SELECT id, nome, cargo, email, telefone, situacao FROM funcionarios WHERE id = $1 LIMIT 1',
        array($funcionario_id)
    );

    if ($resultado && pg_num_rows($resultado) === 1) {
        $funcionario = pg_fetch_assoc($resultado);
        $registro_existente = true;
    } else {
        set_flash('error', 'Funcionário não encontrado.');
        redirect_to('funcionarios.php');
    }
}

if (is_post()) {
    $funcionario_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $situacao = (isset($_POST['situacao']) && $_POST['situacao'] === 'I') ? 'I' : 'A';

    $funcionario = array(
        'id' => $funcionario_id,
        'nome' => $nome,
        'cargo' => $cargo,
        'email' => $email,
        'telefone' => $telefone,
        'situacao' => $situacao
    );

    if ($nome === '') {
        $erros[] = 'Informe o nome do funcionário.';
    }

    if ($cargo === '') {
        $erros[] = 'Selecione um cargo.';
    }

    if ($email === '') {
        $erros[] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if ($telefone === '') {
        $erros[] = 'Informe o telefone.';
    }

    if ($cargo !== '' && !in_array($cargo, $cargos)) {
        $erros[] = 'Cargo inválido.';
    }

    if (empty($erros)) {
        if ($funcionario_id > 0) {
            $duplicado = pg_query_params(
                $conexao,
                'SELECT id FROM funcionarios WHERE email = $1 AND id <> $2 LIMIT 1',
                array($email, $funcionario_id)
            );
        } else {
            $duplicado = pg_query_params(
                $conexao,
                'SELECT id FROM funcionarios WHERE email = $1 LIMIT 1',
                array($email)
            );
        }

        if ($duplicado && pg_num_rows($duplicado) > 0) {
            $erros[] = 'Já existe um funcionário cadastrado com este e-mail.';
        }
    }

    if (empty($erros)) {
        if ($funcionario_id > 0) {
            $salvou = pg_query_params(
                $conexao,
                'UPDATE funcionarios SET nome = $1, cargo = $2, email = $3, telefone = $4, situacao = $5, atualizado_em = CURRENT_TIMESTAMP WHERE id = $6',
                array($nome, $cargo, $email, $telefone, $situacao, $funcionario_id)
            );

            if ($salvou) {
                set_flash('success', 'Funcionário atualizado com sucesso.');
                redirect_to('funcionarios.php');
            }
        } else {
            $salvou = pg_query_params(
                $conexao,
                'INSERT INTO funcionarios (nome, cargo, email, telefone, situacao, criado_em, atualizado_em) VALUES ($1, $2, $3, $4, $5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
                array($nome, $cargo, $email, $telefone, $situacao)
            );

            if ($salvou) {
                set_flash('success', 'Funcionário cadastrado com sucesso.');
                redirect_to('funcionarios.php');
            }
        }

        $erros[] = 'Não foi possível salvar o registro.';
    }
}

$page_title = 'Cadastro de Funcionários';
$page_heading = 'Cadastro de Funcionários';
$active_menu = 'inicio';

require dirname(__FILE__) . '/includes/header.php';

?>

<?php if (!empty($erros)) { ?>
    <div class="flash-message error">
        <?php echo esc(implode(' ', $erros)); ?>
    </div>
<?php } ?>

<section class="content-panel">
    <div class="section-title">
        <span class="section-icon">&#128100;</span>
        <h2><?php echo $modo_visualizacao ? 'Visualização do Funcionário' : 'Cadastro de Funcionários'; ?></h2>
    </div>

    <form action="funcionario_form.php<?php echo $funcionario_id > 0 ? '?id=' . (int) $funcionario_id : ''; ?>" method="post" class="employee-form">
        <input type="hidden" name="id" value="<?php echo esc($funcionario['id']); ?>">

        <div class="form-meta">
            <span><strong>ID:</strong> <?php echo $registro_existente ? esc($funcionario['id']) : 'Automático'; ?></span>
            <span><strong>Status atual:</strong> <?php echo esc(status_label($funcionario['situacao'])); ?></span>
        </div>

        <div class="form-grid">
            <div class="form-field">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Nome" value="<?php echo esc($funcionario['nome']); ?>" <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
            </div>

            <div class="form-field">
                <label for="cargo">Cargo</label>
                <select name="cargo" id="cargo" <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
                    <option value="">Selecione o cargo</option>
                    <?php foreach ($cargos as $cargo_item) { ?>
                        <option value="<?php echo esc($cargo_item); ?>" <?php echo $funcionario['cargo'] === $cargo_item ? 'selected="selected"' : ''; ?>>
                            <?php echo esc($cargo_item); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-field">
                <label for="email">E-mail</label>
                <input type="text" name="email" id="email" placeholder="E-mail" value="<?php echo esc($funcionario['email']); ?>" <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
            </div>

            <div class="form-field">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" placeholder="Telefone" value="<?php echo esc($funcionario['telefone']); ?>" <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
            </div>

            <div class="form-field form-field-full">
                <label>Situação</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="situacao" value="A" <?php echo $funcionario['situacao'] !== 'I' ? 'checked="checked"' : ''; ?> <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
                        <span>Ativo</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="situacao" value="I" <?php echo $funcionario['situacao'] === 'I' ? 'checked="checked"' : ''; ?> <?php echo $modo_visualizacao ? 'disabled="disabled"' : ''; ?>>
                        <span>Inativo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <?php if (!$modo_visualizacao) { ?>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            <?php } ?>

            <?php if ($modo_visualizacao && $funcionario_id > 0) { ?>
                <a href="funcionario_form.php?id=<?php echo (int) $funcionario_id; ?>" class="btn btn-primary">Editar</a>
            <?php } ?>

            <a href="funcionarios.php" class="btn btn-secondary">Voltar</a>
            <a href="logout.php" class="btn btn-secondary">Fechar</a>
        </div>
    </form>
</section>

<?php require dirname(__FILE__) . '/includes/footer.php'; ?>
