# Cadastro de Funcionários

Mini sistema web em PHP puro com PostgreSQL para autenticação simples, cadastro, edição, visualização, exclusão e listagem com busca.

## 🛠 Requisitos

*   **PHP 5** com extensão `pgsql` habilitada.
*   **PostgreSQL**.
*   **pgAdmin4** para criação do banco e execução do script SQL.
*   **Servidor web local** com PHP ou comando `php -S` disponível.

## 📁 Estrutura do Projeto

*   `index.php`: Tela de login.
*   `funcionario_form.php`: Formulário de cadastro, edição e visualização.
*   `funcionarios.php`: Listagem com busca, paginação e exclusão.
*   `config/config.php`: Credenciais de conexão com o banco de dados.
*   `database/schema.sql`: Script de criação das tabelas e carga inicial de dados.

## 🗄️ Passo a passo no pgAdmin4

1.  Abra o **pgAdmin4** e conecte-se ao seu servidor PostgreSQL local.
2.  Crie um novo banco de dados chamado `cadastro_funcionarios`.
3.  Selecione o banco criado e abra a **Query Tool**.
4.  Copie e execute todo o conteúdo do arquivo `database/schema.sql`.
5.  Confirme se as tabelas `usuarios` e `funcionarios` foram geradas com sucesso.

## ⚙️ Configuração do PHP

1.  Abra o arquivo `config/config.php`.
2.  Ajuste os valores de `host`, `port`, `db_name`, `db_user` e `db_password` conforme o seu ambiente.

**Exemplo padrão:**

```php
return array(
    'db_host' => 'localhost',
    'db_port' => '5432',
    'db_name' => 'cadastro_funcionarios',
    'db_user' => 'postgres',
    'db_password' => 'postgres'
);
