<div class="bg-white p-4 shadow rounded">
    <h2 class="text-lg font-semibold mb-4">Seu Carrinho</h2>

    <div class="space-y-4">
        <?php if (count($carrinho) > 0) : ?>
            <?php foreach ($carrinho as $item) : ?>
                <div class="flex justify-between items-center">
                    <span><?= $item['nome'] ?></span>
                    <span><?= $item['quantidade'] ?> x R$ <?= $item['preco'] ?></span>
                    <span>Subtotal: R$ <?= $item['subtotal'] ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-gray-500">Seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>

    <button onclick="finalizarPedido()" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Finalizar Pedido</button>
</div>
