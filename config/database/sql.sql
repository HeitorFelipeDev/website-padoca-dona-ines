
CREATE TABLE `administrador` (
  `codigo_administrador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo_administrador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categoria_produto` (
  `codigo_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cliente` (
  `codigo_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  PRIMARY KEY (`codigo_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `endereco` (
  `codigo_endereco` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) NOT NULL,
  `tipo` enum('Comercial','Residencial') NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `pais` varchar(255) NOT NULL,
  `cep` varchar(9) NOT NULL,
  PRIMARY KEY (`codigo_endereco`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `endereco_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `itens_pedido` (
  `codigo_item_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_produto` int(11) NOT NULL,
  `codigo_pedido` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `subtotal` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`codigo_item_pedido`),
  KEY `codigo_produto` (`codigo_produto`),
  KEY `codigo_pedido` (`codigo_pedido`),
  CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`codigo_produto`) REFERENCES `produto` (`codigo_produto`),
  CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`codigo_pedido`) REFERENCES `pedido` (`codigo_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `itens_sacola` (
  `codigo_item_sacola` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_produto` int(11) NOT NULL,
  `codigo_sacola` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `subtotal` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`codigo_item_sacola`),
  KEY `codigo_produto` (`codigo_produto`),
  KEY `codigo_sacola` (`codigo_sacola`),
  CONSTRAINT `itens_sacola_ibfk_1` FOREIGN KEY (`codigo_produto`) REFERENCES `produto` (`codigo_produto`),
  CONSTRAINT `itens_sacola_ibfk_2` FOREIGN KEY (`codigo_sacola`) REFERENCES `sacola` (`codigo_sacola`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pedido` (
  `codigo_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) NOT NULL,
  `codigo_endereco` int(11) NOT NULL,
  `preco_total` decimal(5,2) NOT NULL,
  `status` enum('Aguardando confirmação da loja','Em preparo','A caminho','Entregue','Finalizado','Cancelado') NOT NULL DEFAULT 'Aguardando confirmação da loja',
  `data_pedido` datetime NOT NULL,
  PRIMARY KEY (`codigo_pedido`),
  KEY `codigo_cliente` (`codigo_cliente`),
  KEY `codigo_endereco` (`codigo_endereco`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo_cliente`),
  CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`codigo_endereco`) REFERENCES `endereco` (`codigo_endereco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `produto` (
  `codigo_produto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_categoria` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(5,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `unidade_venda` enum('un','kg','g','L','ml') NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`codigo_produto`),
  KEY `codigo_categoria` (`codigo_categoria`),
  CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`codigo_categoria`) REFERENCES `categoria_produto` (`codigo_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sacola` (
  `codigo_sacola` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) NOT NULL,
  `status` enum('Aguardando Confirmação','Em andamento','Pagamento confirmado','Preparando pedido','Pedido finalizado','Saiu para entrega','Chegou ao destino final') NOT NULL DEFAULT 'Aguardando Confirmação',
  `data_criacao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`codigo_sacola`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `sacola_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

