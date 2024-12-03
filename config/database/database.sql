CREATE DATABASE IF NOT EXISTS padocadonaines;
USE padocadonaines;

CREATE TABLE IF NOT EXISTS Cliente (
	codigo_cliente INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(12) NULL,
    cpf VARCHAR(14) NOT NULL
);

CREATE TABLE IF NOT EXISTS Endereco (
	codigo_endereco INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    codigo_cliente INT NOT NULL,
    tipo ENUM('Comercial', 'Residencial') NOT NULL,
    logradouro VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    complemento VARCHAR(255),
    bairro VARCHAR(255) NOT NULL,
    cidade VARCHAR(255) NOT NULL,
    estado VARCHAR(255) NOT NULL,
    pais VARCHAR(255) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    
    FOREIGN KEY(codigo_cliente)
		REFERENCES Cliente(codigo_cliente)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Cartao (
	codigo_cartao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    codigo_cliente INT NOT NULL,
    tipo ENUM('Crédito', 'Débito') NOT NULL,
	apelido VARCHAR(255) NOT NULL,
    nome_titular VARCHAR(255) NOT NULL,
    bandeira ENUM('VISA', 'Mastercard') NOT NULL,
    numero INT NOT NULL,
    data_validade DATE NOT NULL,
    cvv VARCHAR(3) NOT NULL,
    
    FOREIGN KEY(codigo_cliente)
		REFERENCES Cliente(codigo_cliente)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Administrador (
	codigo_administrador INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Sacola (
	codigo_sacola INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    codigo_cliente INT NOT NULL,
    status ENUM('Aguardando Confirmação', 'Em andamento', 'Pagamento confirmado', 'Preparando pedido', 'Pedido finalizado', 'Saiu para entrega', 'Chegou ao destino final') DEFAULT 'Aguardando Confirmação' NOT NULL,
    FOREIGN KEY(codigo_cliente)
		REFERENCES Cliente(codigo_cliente)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Metodo_Pagamento (
	codigo_metodo_pagamento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS Categoria_Produto (
	codigo_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Produto (
	codigo_produto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	codigo_categoria INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(5,2) NOT NULL,
    estoque INT NOT NULL,
    unidade_venda ENUM('un', 'kg', 'g', 'L', 'ml') NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    ativo BOOL DEFAULT 1 NOT NULL,
    
	FOREIGN KEY(codigo_categoria)
		REFERENCES Categoria_Produto(codigo_categoria)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Pedido (
	codigo_pedido INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    codigo_cliente INT NOT NULL,
    codigo_endereco INT NOT NULL,
    codigo_metodo_pagamento INT NOT NULL,
    codigo_cartao INT NOT NULL,
    preco_total DECIMAL(5,2) NOT NULL,
    troco DECIMAL(5,2),
    status ENUM('Aguardando confirmação da loja', 'Em preparo', 'A caminho', 'Entregue', 'Finalizado', 'Cancelado') DEFAULT 'Aguardando confirmação da loja' NOT NULL,
    data_pedido DATETIME NOT NULL,
    data_entrega DATETIME NOT NULL,
	
    FOREIGN KEY(codigo_cliente)
		REFERENCES Cliente(codigo_cliente)
        ON DELETE CASCADE,
	FOREIGN KEY(codigo_endereco)
		REFERENCES Endereco(codigo_endereco)
        ON DELETE CASCADE,
	FOREIGN KEY(codigo_metodo_pagamento)
		REFERENCES Metodo_Pagamento(codigo_metodo_pagamento)
        ON DELETE CASCADE,
	FOREIGN KEY(codigo_cartao)
		REFERENCES Cartao(codigo_cartao)
        ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS Itens_Sacola(
	codigo_item_sacola INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	codigo_produto INT NOT NULL,
    codigo_sacola INT NOT NULL,
    quantidade INT NOT NULL,
    subtotal DECIMAL(5, 2),
    
    FOREIGN KEY(codigo_produto)
		REFERENCES Produto(codigo_produto)
        ON DELETE CASCADE,
	FOREIGN KEY(codigo_sacola)
		REFERENCES Sacola(codigo_sacola)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Itens_Pedido (
	codigo_item_pedido INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	codigo_produto INT NOT NULL,
    codigo_pedido INT NOT NULL,
    quantidade INT NOT NULL,
    subtotal DECIMAL(5, 2),
    
    FOREIGN KEY(codigo_produto)
		REFERENCES Produto(codigo_produto)
        ON DELETE CASCADE,
	FOREIGN KEY(codigo_pedido)
		REFERENCES Pedido(codigo_pedido)     
        ON DELETE CASCADE      
);

