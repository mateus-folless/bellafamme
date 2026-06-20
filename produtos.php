-- =========================================================
-- VERSÃO PARA HOSPEDAGEM COMPARTILHADA (ProFreeHost / cPanel)
-- Não contém CREATE DATABASE nem USE, pois o cPanel não
-- permite criar bancos via SQL — o banco já deve existir
-- (criado pelo menu "MySQL Databases" do cPanel) e este
-- script deve ser importado DENTRO dele pelo phpMyAdmin.
-- =========================================================

-- =========================================================
-- TABELA: categorias
-- =========================================================
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(150)
) ENGINE=InnoDB;

-- =========================================================
-- TABELA: produtos
-- Possui chave estrangeira (FK) para categorias
-- =========================================================
CREATE TABLE produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    tamanho VARCHAR(10) NOT NULL,
    cor VARCHAR(30),
    estoque INT NOT NULL DEFAULT 0,
    imagem VARCHAR(255),
    id_categoria INT NOT NULL,
    CONSTRAINT fk_produto_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================================================
-- TABELA: clientes
-- =========================================================
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================
-- TABELA: pedidos
-- Possui chave estrangeira (FK) para clientes
-- =========================================================
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'Pendente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_pedido_cliente
        FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- TABELA: pedido_produtos (TABELA ASSOCIATIVA - N:N)
-- Relacionamento muitos-para-muitos entre pedidos e produtos:
--   - Um pedido pode ter vários produtos
--   - Um produto pode estar em vários pedidos
-- =========================================================
CREATE TABLE pedido_produtos (
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_pedido, id_produto),
    CONSTRAINT fk_pp_pedido
        FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pp_produto
        FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================================================
-- DADOS DE EXEMPLO
-- =========================================================

INSERT INTO categorias (nome, descricao) VALUES
('Vestidos', 'Vestidos para todas as ocasiões'),
('Blusas', 'Blusas e camisetas femininas'),
('Calças', 'Calças, jeans e leggings'),
('Saias', 'Saias curtas, midi e longas'),
('Acessórios', 'Bolsas, lenços e bijuterias');

INSERT INTO produtos (nome, descricao, preco, tamanho, cor, estoque, imagem, id_categoria) VALUES
('Vestido Floral Midi', 'Vestido midi com estampa floral, tecido leve.', 159.90, 'M', 'Estampado', 12, 'img/vestido-floral.svg', 1),
('Vestido Festa Longo', 'Vestido longo elegante para festas.', 289.90, 'P', 'Vinho', 5, 'img/vestido-festa.svg', 1),
('Blusa de Linho Básica', 'Blusa de linho confortável para o dia a dia.', 79.90, 'G', 'Branco', 30, 'img/blusa-linho.svg', 2),
('Blusa Cropped Listrada', 'Blusa cropped com listras horizontais.', 64.90, 'P', 'Azul/Branco', 18, 'img/blusa-cropped.svg', 2),
('Calça Jeans Skinny', 'Calça jeans skinny cintura alta.', 139.90, '38', 'Azul Escuro', 20, 'img/calca-jeans.svg', 3),
('Legging Fitness', 'Legging com tecido tecnológico para atividades físicas.', 89.90, 'M', 'Preto', 25, 'img/legging-fitness.svg', 3),
('Saia Midi Plissada', 'Saia midi plissada, ideal para o trabalho.', 119.90, 'M', 'Verde Musgo', 10, 'img/saia-midi.svg', 4),
('Saia Jeans Curta', 'Saia jeans curta com botões frontais.', 99.90, '36', 'Azul Claro', 0, 'img/saia-jeans.svg', 4),
('Bolsa Transversal Couro', 'Bolsa transversal em couro sintético.', 129.90, 'Único', 'Caramelo', 15, 'img/bolsa-couro.svg', 5),
('Lenço de Seda Estampado', 'Lenço de seda com estampa exclusiva.', 49.90, 'Único', 'Multicolor', 22, 'img/lenco-seda.svg', 5);

INSERT INTO clientes (nome, email, senha, telefone, endereco) VALUES
('Maria Souza', 'maria.souza@email.com', '$2y$10$abcdefghijklmnopqrstuv', '(44) 99999-0001', 'Rua das Flores, 123 - Peabiru/PR'),
('Ana Lima', 'ana.lima@email.com', '$2y$10$abcdefghijklmnopqrstuv', '(44) 99999-0002', 'Av. Brasil, 456 - Peabiru/PR');

INSERT INTO pedidos (id_cliente, status, total) VALUES
(1, 'Concluído', 239.80),
(2, 'Pendente', 159.90);

-- Pedido 1: Maria comprou Blusa de Linho (2x) + Lenço de Seda (1x)
INSERT INTO pedido_produtos (id_pedido, id_produto, quantidade, preco_unitario) VALUES
(1, 3, 2, 79.90),
(1, 10, 1, 49.90);

-- Pedido 2: Ana comprou Vestido Floral (1x)
INSERT INTO pedido_produtos (id_pedido, id_produto, quantidade, preco_unitario) VALUES
(2, 1, 1, 159.90);
