<?php
    require_once 'config/database/connection.php';
    require_once 'controllers/ProdutoController.php';

    $produtoController = new ProdutoController();
    $produtos = $produtoController->listarProdutos();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padaria - E-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/produto.js" defer></script>
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <button onclick="window.history.back()" class="text-blue-500">Voltar</button>
            <div class="flex space-x-4">
                <a href="views/carrinho/listar.php">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c0-3.866-3.134-7-7-7S2 7.134 2 11a7 7 0 0012 5.743A4.5 4.5 0 0116 11z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Produtos</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($produtos as $produto) : ?>
                <div class="bg-white shadow rounded p-4">
                    <img src="assets/images/<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>" class="h-40 w-full object-cover">
                    <h2 class="text-lg font-semibold mt-2"><?= $produto['nome'] ?></h2>
                    <p class="text-gray-500"><?= $produto['descricao'] ?></p>
                    <p class="text-blue-600 font-bold mt-2">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <button onclick="adicionarAoCarrinho(<?= $produto['codigo_produto'] ?>)" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
                        Adicionar ao Carrinho
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>     

</body>
</html>
