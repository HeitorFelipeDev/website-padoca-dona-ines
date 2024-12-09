<?php

    require_once '../models/perfilModel.php';

    session_start();

    $action = $_GET['action'] ?? '';

    switch ($action) {

        case 'getPerfil':
            echo json_encode(getPerfil($_SESSION['codigo_cliente']));
            break;

        case 'updatePerfil':
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(updatePerfil($_SESSION['codigo_cliente'], $data));
            break;

        case 'getPedidos':
            echo json_encode(getPedidos($_SESSION['codigo_cliente']));
            break;

        case 'cancelarPedido':
            $id = $_GET['id'];
            echo json_encode(cancelarPedido($id));
            break;

        case 'alterarSenha':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!empty($data['novaSenha'])) echo json_encode(alterarSenha($_SESSION['codigo_cliente'], $data['novaSenha']));
            else echo json_encode(["message" => "Nova senha não fornecida."]);
            break;

        case 'getEnderecos':
            echo json_encode(getEnderecos($_SESSION['codigo_cliente']));
            break;
    
        case 'adicionarEndereco':
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(adicionarEndereco($_SESSION['codigo_cliente'], $data['endereco']));
            break;
    
        case 'editarEndereco':
            $id = $_GET['id'];
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(editarEndereco($_SESSION['codigo_cliente'], $id, $data['endereco']));
            break;
    
        case 'excluirEndereco':
            $id = $_GET['id'];
            echo json_encode(excluirEndereco($_SESSION['codigo_cliente'], $id));
            break;

    }
