<?php

require_once '../config/database/connection.php';

// Obtém os dados do perfil do cliente
function getPerfil($clienteId) {
    global $pdo;
    $query = $pdo->prepare("SELECT nome, email, cpf FROM cliente WHERE codigo_cliente = ?");
    $query->execute([$clienteId]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Atualiza os dados do perfil do cliente
function updatePerfil($clienteId, $data) {
    global $pdo;

    $fields = [];
    $params = [];

    if (!empty($data['nome'])) {
        $fields[] = "nome = ?";
        $params[] = $data['nome'];
    }
    if (!empty($data['email'])) {
        $fields[] = "email = ?";
        $params[] = $data['email'];
    }
    if (!empty($data['cpf'])) {
        $fields[] = "cpf = ?";
        $params[] = str_replace(['.', '-'], '', $data['cpf']);
    }
    if (!empty($data['senha'])) {
        $fields[] = "senha = ?";
        $params[] = password_hash($data['senha'], PASSWORD_BCRYPT);
    }

    $params[] = $clienteId;

    $queryStr = "UPDATE cliente SET " . implode(", ", $fields) . " WHERE codigo_cliente = ?";
    $query = $pdo->prepare($queryStr);
    $query->execute($params);

    return ["message" => "Perfil atualizado com sucesso!"];
}

// Obtém os pedidos do cliente
function getPedidos($clienteId) {
    global $pdo;
    $query = $pdo->prepare("
        SELECT p.codigo_pedido, p.preco_total, p.status, p.data_pedido, e.logradouro, e.numero, e.bairro, e.cidade, e.estado, e.cep
        FROM pedido p
        JOIN endereco e ON p.codigo_endereco = e.codigo_endereco
        WHERE p.codigo_cliente = ?
        ORDER BY p.codigo_pedido DESC
    ");
    $query->execute([$clienteId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Cancela um pedido
function cancelarPedido($pedidoId) {
    global $pdo;
    $query = $pdo->prepare("UPDATE pedido SET status = 'Cancelado' WHERE codigo_pedido = ?");
    $query->execute([$pedidoId]);
    return ["message" => "Pedido cancelado com sucesso!"];
}

// Altera a senha do cliente
function alterarSenha($clienteId, $novaSenha) {
    global $pdo;
    $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
    $query = $pdo->prepare("UPDATE cliente SET senha = ? WHERE codigo_cliente = ?");
    $query->execute([$senhaHash, $clienteId]);
    return ["message" => "Senha alterada com sucesso!"];
}

// Obtém os endereços do cliente
function getEnderecos($clienteId) {
    global $pdo;
    try {
        $query = $pdo->prepare("SELECT codigo_endereco, tipo, logradouro, numero, complemento, bairro, cidade, estado, pais, cep
                                FROM endereco
                                WHERE codigo_cliente = ?");
        $query->execute([$clienteId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return ["status" => "error", "message" => "Erro ao carregar endereços."];
    }
}

// Adiciona um endereço para o cliente
function adicionarEndereco($clienteId, $dadosEndereco) {
    global $pdo;
    try {
        $query = $pdo->prepare("
            INSERT INTO endereco (codigo_cliente, tipo, logradouro, numero, complemento, bairro, cidade, estado, pais, cep) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $query->execute([
            $clienteId,
            $dadosEndereco['tipo'] ?? 'Residencial', // Valor padrão se não fornecido
            $dadosEndereco['logradouro'],
            $dadosEndereco['numero'],
            $dadosEndereco['complemento'] ?? null,
            $dadosEndereco['bairro'],
            $dadosEndereco['cidade'],
            $dadosEndereco['estado'],
            $dadosEndereco['pais'] ?? 'Brasil',
            $dadosEndereco['cep']
        ]);
        return ["status" => "success", "message" => "Endereço adicionado com sucesso!"];
    } catch (Exception $e) {
        return ["status" => "error", "message" => "Erro ao adicionar endereço."];
    }
}

// Edita um endereço do cliente
function editarEndereco($clienteId, $enderecoId, $dadosEndereco) {
    global $pdo;
    try {
        $query = $pdo->prepare("
            UPDATE endereco 
            SET tipo = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, pais = ?, cep = ?
            WHERE codigo_endereco = ? AND codigo_cliente = ?
        ");
        $query->execute([
            $dadosEndereco['tipo'] ?? 'Residencial', // Valor padrão se não fornecido
            $dadosEndereco['logradouro'],
            $dadosEndereco['numero'],
            $dadosEndereco['complemento'] ?? null,
            $dadosEndereco['bairro'],
            $dadosEndereco['cidade'],
            $dadosEndereco['estado'],
            $dadosEndereco['pais'] ?? 'Brasil',
            $dadosEndereco['cep'],
            $enderecoId,
            $clienteId
        ]);
        return ["status" => "success", "message" => "Endereço atualizado com sucesso!"];
    } catch (Exception $e) {
        return ["status" => "error", "message" => "Erro ao atualizar endereço."];
    }
}

// Exclui um endereço do cliente
function excluirEndereco($clienteId, $enderecoId) {
    global $pdo;
    try {
        $query = $pdo->prepare("DELETE FROM endereco WHERE codigo_endereco = ? AND codigo_cliente = ?");
        $query->execute([$enderecoId, $clienteId]);
        return ["status" => "success", "message" => "Endereço excluído com sucesso!"];
    } catch (Exception $e) {
        return ["status" => "error", "message" => "Erro ao excluir endereço."];
    }
}
