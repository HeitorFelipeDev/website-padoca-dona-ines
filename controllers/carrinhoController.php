<?php

    require_once '../models/carrinhoModel.php';

    session_start();

    $action = $_GET['action'] ?? '';

    $codigoCliente = $_SESSION['codigo_cliente'] ?? null; // Exemplo para verificar da sessão

    if (!$codigoCliente) {
        die('Erro: Cliente não autenticado ou código de cliente ausente.');
    }

    switch ($action) {
        case 'listar':
            criarSacolaSeNecessario($codigoCliente);
            echo json_encode(listarItensSacola($codigoCliente));
            break;

        case 'adicionar':
            $data = json_decode(file_get_contents('php://input'), true);
            adicionarAoCarrinho($codigoCliente, $data['codigo_produto']);
            break;

        case 'limpar':
            limparSacola($codigoCliente);
            break;

        case 'finalizar':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoEndereco = $data['codigo_endereco'] ?? null;
            if (!$codigoEndereco) 
                die('Erro: Endereço não selecionado.');
            finalizarPedido($codigoCliente, $codigoEndereco);
            break;

        case 'remover':
            $codigoItem = $_GET['codigo_item'] ?? null;
            if ($codigoItem) {
                removerItemSacola($codigoItem);
            }
            break;

        case 'alterarQuantidade':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoItem = $data['codigo_item'] ?? null;
            $quantidade = $data['quantidade'] ?? 0;
        
            if ($codigoItem && $quantidade !== 0) {
                alterarQuantidadeItem($codigoItem, $quantidade);
            }
            break;

        case 'listarEnderecos':
            echo json_encode(listarEnderecos($codigoCliente));
            break;
            
        case 'contar':
            echo json_encode(contarItensCarrinho($codigoCliente));
            break;

    }
