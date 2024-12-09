<?php

    require_once '../models/produtosModel.php';

    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'listar':
            $categoria = $_GET['categoria'] ?? null; // Verifica se há um filtro de categoria
            $produtos = listarProdutos($categoria);
            header('Content-Type: application/json');
            echo json_encode($produtos);
            break;

        case 'listarCategorias':
            $categorias = listarCategorias();
            header('Content-Type: application/json');
            echo json_encode($categorias);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Ação inválida']);
            break;
    }

    function listarProdutos($categoria = null) {
        return buscarProdutosAtivos($categoria);
    }

    function listarCategorias() {
        return buscarCategoriasAtivas();
    }
