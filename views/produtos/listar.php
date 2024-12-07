<?php

session_start();
require_once '../../config/database/connection.php';

if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = 'cliente';
}
$isAdmin = $_SESSION['user_role'] === 'admin';

if (!$pdo) {
    die("Conexão com o banco de dados não foi estabelecida.");
}

if (!isset($_SESSION['codigo_sacola'])) {
    $stmt = $pdo->prepare("INSERT INTO sacola (data_criacao) VALUES (NOW())");
    $stmt->execute();
    $_SESSION['codigo_sacola'] = $pdo->lastInsertId();
}

function getCategories(PDO $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM categoria_produto");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProducts($category = null) {
    global $pdo;
    $sql = "SELECT p.codigo_produto, p.nome, p.descricao, p.preco, p.imagem, c.nome AS categoria
            FROM produto p
            JOIN categoria_produto c ON p.codigo_categoria = c.codigo_categoria
            WHERE p.ativo = 1";
    if ($category) {
        $sql .= " AND p.codigo_categoria = :category";
    }

    $stmt = $pdo->prepare($sql);
    if ($category) {
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$categories = getCategories($pdo);
$products = getProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">
        <button onclick="history.back()" class="text-lg font-semibold hover:underline">Voltar</button>
        <div class="flex items-center space-x-4">
            <button id="cart-icon" onclick="toggleCart()" class="relative group">
                <svg class="w-6 h-6 group-hover:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l3.6-8H6.4M7 13l-2 9h10l2-9m-2-4H6M7 13l-4-8m16 0h-2.4M6.4 5L5 3m14 8h.01" />
                </svg>
                <span id="cart-count" class="absolute -top-2 -right-2 text-xs bg-red-500 text-white rounded-full px-1">0</span>
            </button>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto p-6">
    <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                <img src="../../assets/images/<?= $product['imagem'] ?>" alt="<?= $product['nome'] ?>" class="w-full h-40 object-cover rounded-lg mb-4">
                <h2 class="text-lg font-bold"><?= $product['nome'] ?></h2>
                <p class="text-sm text-gray-600"><?= $product['descricao'] ?></p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-blue-600 font-bold">R$ <?= number_format($product['preco'], 2, ',', '.') ?></span>
                    <button onclick="addToCart(<?= $product['codigo_produto'] ?>, <?= $product['preco'] ?>)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Adicionar</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
    const sacolaCodigo = <?= json_encode($_SESSION['codigo_sacola']) ?>;
</script>
<script src="../../assets/js/produto.js"></script>
</body>
</html>
