CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    usuario VARCHAR(60) NOT NULL UNIQUE,
    senha_hash VARCHAR(40) NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS funcionarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cargo VARCHAR(80) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(30) NOT NULL,
    situacao CHAR(1) NOT NULL DEFAULT 'A',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_funcionarios_situacao CHECK (situacao IN ('A', 'I'))
);

INSERT INTO usuarios (nome, usuario, senha_hash, ativo)
SELECT 'Administrador', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', TRUE
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE usuario = 'admin'
);

INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
SELECT 'João Silva', 'Administrador', 'joao.silva@empresa.com', '(11) 99999-1001', 'A'
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'joao.silva@empresa.com'
);

INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
SELECT 'Ana Mendes', 'Gerente', 'ana.mendes@empresa.com', '(11) 99999-1002', 'A'
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'ana.mendes@empresa.com'
);

INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
SELECT 'Pedro Souza', 'Assistente', 'pedro.souza@empresa.com', '(11) 99999-1003', 'A'
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'pedro.souza@empresa.com'
);

INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
SELECT 'Carla Oliveira', 'Administrador', 'carla.oliveira@empresa.com', '(11) 99999-1004', 'A'
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'carla.oliveira@empresa.com'
);

INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
SELECT 'Lucas Martins', 'Assistente', 'lucas.martins@empresa.com', '(11) 99999-1005', 'I'
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'lucas.martins@empresa.com'
);

