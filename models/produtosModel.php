<?php

    require_once '../config/database/connection.php';

    function buscarProdutosAtivos($categoria = null) {
        global $pdo;

        $query = "SELECT codigo_produto, nome, preco, imagem FROM Produto WHERE ativo = 1";

        if ($categoria) {
            $query .= " AND codigo_categoria = :categoria";
        }

        $stmt = $pdo->prepare($query);

        if ($categoria) {
            $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscarCategoriasAtivas() {
        global $pdo;

        $query = "SELECT codigo_categoria AS id, nome, imagem FROM Categoria_Produto";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
