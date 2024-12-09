<?php
require_once '../config/database/connection.php';

function listarItensSacola($codigoCliente) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.nome, i.codigo_item_sacola, i.quantidade, i.subtotal 
                            FROM Itens_Sacola i 
                            JOIN Produto p ON i.codigo_produto = p.codigo_produto 
                            WHERE i.codigo_sacola = (SELECT codigo_sacola FROM Sacola WHERE codigo_cliente = ? AND status = 'Aguardando Confirmação')");
    $stmt->execute([$codigoCliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adicionarAoCarrinho($codigoCliente, $codigoProduto) {
    global $pdo;
    $sacola = getSacolaAtiva($codigoCliente);
    $stmt = $pdo->prepare("INSERT INTO Itens_Sacola (codigo_produto, codigo_sacola, quantidade, subtotal) 
                            VALUES (?, ?, 1, (SELECT preco FROM Produto WHERE codigo_produto = ?))");
    $stmt->execute([$codigoProduto, $sacola, $codigoProduto]);
}

function limparSacola($codigoCliente) {
    global $pdo;
    $sacola = getSacolaAtiva($codigoCliente);
    $pdo->prepare("DELETE FROM Itens_Sacola WHERE codigo_sacola = ?")->execute([$sacola]);
}

function finalizarPedido($codigoCliente, $codigoEndereco) {

    global $pdo;
    $pdo->beginTransaction();

    try {

        $sacola = getSacolaAtiva($codigoCliente);

        $stmt = $pdo->prepare("SELECT SUM(subtotal) AS total FROM Itens_Sacola WHERE codigo_sacola = ?");
        $stmt->execute([$sacola]);
        $total = $stmt->fetchColumn();

        $stmt = $pdo->prepare("INSERT INTO Pedido (codigo_cliente, codigo_endereco, preco_total, status, data_pedido)
                        VALUES (?, ?, ?, 'Aguardando confirmação da loja', NOW())");
        $stmt->execute([$codigoCliente, $codigoEndereco, $total]);

        $codigoPedido = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO Itens_Pedido (codigo_produto, codigo_pedido, quantidade, subtotal)
                                SELECT codigo_produto, ?, quantidade, subtotal FROM Itens_Sacola WHERE codigo_sacola = ?");
        $stmt->execute([$codigoPedido, $sacola]);

        $pdo->prepare("DELETE FROM Itens_Sacola WHERE codigo_sacola = ?")->execute([$sacola]);
        $pdo->prepare("UPDATE Sacola SET status = 'Em andamento' WHERE codigo_sacola = ?")->execute([$sacola]);

        $pdo->commit();

    } catch (Exception $e) {

        $pdo->rollBack();
        throw $e;

    }

}

function getSacolaAtiva($codigoCliente) {
    criarSacolaSeNecessario($codigoCliente); // Garante que a sacola ativa existe
    global $pdo;
    $stmt = $pdo->prepare("SELECT codigo_sacola FROM Sacola WHERE codigo_cliente = ? AND status = 'Aguardando Confirmação'");
    $stmt->execute([$codigoCliente]);
    return $stmt->fetchColumn();
}

function criarSacolaSeNecessario($codigoCliente) {
    global $pdo;
    // Verifica se já existe uma sacola ativa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Sacola WHERE codigo_cliente = ? AND status = 'Aguardando Confirmação'");
    $stmt->execute([$codigoCliente]);
    if ($stmt->fetchColumn() == 0) {
        // Insere uma nova sacola com `data_criacao`
        $pdo->prepare("INSERT INTO Sacola (codigo_cliente, status, data_criacao) VALUES (?, 'Aguardando Confirmação', NOW())")->execute([$codigoCliente]);
    }
}

function removerItemSacola($codigoItem) {
    global $pdo;
    $pdo->prepare("DELETE FROM Itens_Sacola WHERE codigo_item_sacola = ?")->execute([$codigoItem]);
}


function alterarQuantidadeItem($codigoItem, $quantidade) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT quantidade, codigo_produto FROM Itens_Sacola WHERE codigo_item_sacola = ?");
    $stmt->execute([$codigoItem]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        error_log("Item não encontrado: $codigoItem");
        throw new Exception("Item não encontrado.");
    }

    $novaQuantidade = $item['quantidade'] + $quantidade;

    if ($novaQuantidade <= 0) {
        removerItemSacola($codigoItem);
        error_log("Item removido: $codigoItem");
    } else {
        $stmt = $pdo->prepare("UPDATE Itens_Sacola 
                               SET quantidade = ?, subtotal = ? * quantidade 
                               WHERE codigo_item_sacola = ?");
        $stmt->execute([
            $novaQuantidade,
            getPrecoProduto($item['codigo_produto']),
            $codigoItem
        ]);
        error_log("Quantidade atualizada para $novaQuantidade para o item: $codigoItem");
    }
}

function getPrecoProduto($codigoProduto) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT preco FROM Produto WHERE codigo_produto = ?");
    $stmt->execute([$codigoProduto]);
    return $stmt->fetchColumn();
}

function listarEnderecos($codigoCliente) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT codigo_endereco, logradouro, numero, complemento, bairro, cidade, estado, cep 
                           FROM Endereco WHERE codigo_cliente = ?");
    $stmt->execute([$codigoCliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarItensCarrinho($codigoCliente) {
    global $pdo;
    $stmt = $pdo->prepare(" SELECT COUNT(*) AS total_itens FROM Itens_Sacola AS itens
                            JOIN Sacola AS sacola ON itens.codigo_sacola = sacola.codigo_sacola
                            WHERE sacola.codigo_cliente = ?
    ");
    $stmt->execute([$codigoCliente]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['total_itens'] ?? 0;
}