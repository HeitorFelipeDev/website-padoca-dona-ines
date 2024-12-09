<?php
    require_once '../models/produtosModel.php';

    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'listar':
            echo json_encode(listarProdutos());
            break;
    }

    function listarProdutos() {
        return buscarProdutosAtivos();
    }
