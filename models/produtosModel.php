<?php

    require_once '../config/database/connection.php';

    function buscarProdutosAtivos() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Produto WHERE ativo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
