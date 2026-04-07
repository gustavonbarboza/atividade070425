# Cadastro de Funcionários

Mini sistema web em PHP puro com PostgreSQL para autenticação simples, cadastro, edição, visualização, exclusão e listagem com busca.

## Requisitos

- PHP 5 com extensão `pgsql` habilitada
- PostgreSQL
- pgAdmin4 para criar o banco e executar o script SQL
- Servidor web local com PHP ou `php -S` disponível

## Estrutura

- `index.php`: tela de login
- `funcionario_form.php`: cadastro, edição e visualização
- `funcionarios.php`: listagem com busca, paginação e exclusão
- `config/config.php`: credenciais do banco
- `database/schema.sql`: criação das tabelas e carga inicial

## Passo a passo no pgAdmin4

1. Abra o pgAdmin4 e conecte no seu servidor PostgreSQL local.
2. Crie um banco chamado `cadastro_funcionarios`.
3. Selecione esse banco e abra a Query Tool.
4. Execute todo o conteúdo do arquivo `database/schema.sql`.
5. Confirme que as tabelas `usuarios` e `funcionarios` foram criadas.

## Configuração do PHP

1. Abra o arquivo `config/config.php`.
2. Ajuste os valores de host, porta, nome do banco, usuário e senha.
3. Exemplo padrão:

```php
return array(
    'db_host' => 'localhost',
    'db_port' => '5432',
    'db_name' => 'cadastro_funcionarios',
    'db_user' => 'postgres',
    'db_password' => 'postgres'
);
```

## Como executar

### Opção 1: servidor embutido do PHP

Se o comando `php` estiver disponível no terminal:

```bash
php -S localhost:8080
```

Depois abra:

```text
http://localhost:8080
```

### Opção 2: Apache, XAMPP, MAMP ou similar

1. Publique esta pasta dentro do diretório web do seu ambiente PHP.
2. Acesse a URL local configurada pelo seu servidor.

## Acesso inicial

- Usuário: `admin`
- Senha: `123456`

## Funcionalidades entregues

- Login com sessão
- Logout
- Recuperação de senha orientativa
- Cadastro de funcionário
- Edição de funcionário
- Visualização de registro
- Exclusão com confirmação
- Busca por nome, cargo ou e-mail
- Paginação com 5 registros por página
- Layout em HTML e CSS sem frameworks

## Observação

Esta versão usa autenticação simples com `sha1` para manter compatibilidade com PHP5. Em um cenário real, o ideal é migrar para uma versão mais nova do PHP e usar hash de senha moderno.
