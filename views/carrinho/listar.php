<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <script src="../../assets/js/carrinho.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Seu Carrinho</h1>
        <div id="carrinho" class="grid gap-6">
            <!-- Produtos do carrinho serão carregados aqui -->
        </div>
        <div id="enderecosContainer">
            <label for="enderecos">Selecione o endereço:</label>
            <select id="enderecos"></select>
        </div>
        <div class="mt-6 flex justify-between items-center">
            <button class="bg-red-500 text-white px-4 py-2 rounded" id="limparCarrinho">Limpar Carrinho</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded" id="finalizarPedido">Finalizar Pedido</button>
        </div>
    </div>
</body>
</html>
