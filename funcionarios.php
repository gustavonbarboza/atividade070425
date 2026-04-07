<?php

require_once dirname(__FILE__) . '/includes/auth.php';
require_once dirname(__FILE__) . '/includes/db.php';

$conexao = db_connect();

if (is_post() && !empty($_POST['excluir_id'])) {
    $excluir_id = (int) $_POST['excluir_id'];
    $removeu = pg_query_params(
        $conexao,
        'DELETE FROM funcionarios WHERE id = $1',
        array($excluir_id)
    );

    if ($removeu) {
        set_flash('success', 'Funcionário excluído com sucesso.');
    } else {
        set_flash('error', 'Não foi possível excluir o funcionário.');
    }

    redirect_to('funcionarios.php');
}

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$pagina = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$por_pagina = 5;
$pagina = $pagina > 0 ? $pagina : 1;
$offset = 0;
$total_registros = 0;
$total_paginas = 1;
$funcionarios = array();

if ($busca !== '') {
    $termo = '%' . $busca . '%';
    $resultado_total = pg_query_params(
        $conexao,
        'SELECT COUNT(*) AS total FROM funcionarios WHERE nome ILIKE $1 OR cargo ILIKE $1 OR email ILIKE $1',
        array($termo)
    );
} else {
    $resultado_total = pg_query(
        $conexao,
        'SELECT COUNT(*) AS total FROM funcionarios'
    );
}

if ($resultado_total) {
    $linha_total = pg_fetch_assoc($resultado_total);
    $total_registros = (int) $linha_total['total'];
}

$total_paginas = $total_registros > 0 ? (int) ceil($total_registros / $por_pagina) : 1;

if ($pagina > $total_paginas) {
    $pagina = $total_paginas;
}

$offset = ($pagina - 1) * $por_pagina;

if ($busca !== '') {
    $termo = '%' . $busca . '%';
    $resultado = pg_query_params(
        $conexao,
        'SELECT id, nome, cargo, email, telefone, situacao FROM funcionarios WHERE nome ILIKE $1 OR cargo ILIKE $1 OR email ILIKE $1 ORDER BY id ASC LIMIT ' . $por_pagina . ' OFFSET ' . $offset,
        array($termo)
    );
} else {
    $resultado = pg_query(
        $conexao,
        'SELECT id, nome, cargo, email, telefone, situacao FROM funcionarios ORDER BY id ASC LIMIT ' . $por_pagina . ' OFFSET ' . $offset
    );
}

if ($resultado) {
    while ($linha = pg_fetch_assoc($resultado)) {
        $funcionarios[] = $linha;
    }
}

$page_title = 'Listagem de Funcionários';
$page_heading = 'Listagem de Funcionários';
$active_menu = 'listagem';

require dirname(__FILE__) . '/includes/header.php';

?>

<section class="listing-toolbar">
    <form action="funcionarios.php" method="get" class="search-form">
        <div class="search-input">
            <span class="search-icon">&#128269;</span>
            <input type="text" name="busca" placeholder="Buscar funcionário..." value="<?php echo esc($busca); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Pesquisar</button>
        <a href="funcionario_form.php" class="btn btn-primary">Novo Funcionário</a>
    </form>
</section>

<section class="content-panel">
    <div class="table-wrap">
        <table class="employee-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>E-mail</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($funcionarios)) { ?>
                    <tr>
                        <td colspan="6" class="empty-state">Nenhum funcionário encontrado.</td>
                    </tr>
                <?php } ?>

                <?php foreach ($funcionarios as $funcionario) { ?>
                    <tr>
                        <td><?php echo esc($funcionario['id']); ?></td>
                        <td><?php echo esc($funcionario['nome']); ?></td>
                        <td><?php echo esc($funcionario['cargo']); ?></td>
                        <td><?php echo esc($funcionario['email']); ?></td>
                        <td>
                            <span class="status-pill <?php echo esc(status_class($funcionario['situacao'])); ?>">
                                <?php echo esc(status_label($funcionario['situacao'])); ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="funcionario_form.php?id=<?php echo (int) $funcionario['id']; ?>" class="icon-btn" title="Editar">&#9998;</a>
                                <a href="funcionario_form.php?id=<?php echo (int) $funcionario['id']; ?>&visualizar=1" class="icon-btn" title="Visualizar">&#128065;</a>
                                <form action="funcionarios.php" method="post" class="inline-form" onsubmit="return confirm('Deseja excluir este funcionário?');">
                                    <input type="hidden" name="excluir_id" value="<?php echo (int) $funcionario['id']; ?>">
                                    <button type="submit" class="icon-btn icon-btn-danger" title="Excluir">&#128465;</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
            <a href="<?php echo esc(preserve_query_with_page($i)); ?>" class="page-link <?php echo $i === $pagina ? 'is-current' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>

        <?php if ($pagina < $total_paginas) { ?>
            <a href="<?php echo esc(preserve_query_with_page($pagina + 1)); ?>" class="page-link page-link-next">Próximo &gt;&gt;</a>
        <?php } ?>
    </div>
</section>

<?php require dirname(__FILE__) . '/includes/footer.php'; ?>

