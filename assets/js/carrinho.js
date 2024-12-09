document.addEventListener('DOMContentLoaded', () => {
    const carrinhoContainer = document.getElementById('carrinho');
    const limparCarrinhoBtn = document.getElementById('limparCarrinho');
    const finalizarPedidoBtn = document.getElementById('finalizarPedido');
    const enderecosContainer = document.getElementById('enderecos');

    const carregarCarrinho = async () => {
        const response = await fetch('../../controllers/carrinhoController.php?action=listar');
        const carrinho = await response.json();
    
        console.log(carrinho); // Log para inspecionar os dados retornados
        const total = carrinho.reduce((sum, item) => sum + parseFloat(item.subtotal), 0);
    
        carrinhoContainer.innerHTML = carrinho.map(item => `
            <div class="p-4 border rounded flex justify-between items-center">
                <span>${item.nome}</span>
                <div class="flex items-center">
                    <button class="bg-gray-300 px-2 py-1 rounded" onclick="alterarQuantidade(${item.codigo_item_sacola}, -1)">-</button>
                    <span class="mx-2">${item.quantidade}</span>
                    <button class="bg-gray-300 px-2 py-1 rounded" onclick="alterarQuantidade(${item.codigo_item_sacola}, 1)">+</button>
                </div>
                <span>R$ ${item.subtotal}</span>
                <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="removerItem(${item.codigo_item_sacola})">Remover</button>
            </div>
        `).join('');
        carrinhoContainer.insertAdjacentHTML('beforeend', `
            <div class="p-4 border-t text-right text-lg font-bold">Total: R$ ${total.toFixed(2)}</div>
        `);
    };

    const carregarEnderecos = async () => {
        const response = await fetch('../../controllers/carrinhoController.php?action=listarEnderecos');
        const enderecos = await response.json();
    
        enderecosContainer.innerHTML = `
            <label for="enderecos">Selecione o endereço:</label>
            <select id="enderecos" class="border p-2 rounded w-full">
                ${enderecos.map(endereco => `
                    <option value="${endereco.codigo_endereco}">
                        ${endereco.logradouro}, ${endereco.numero}, ${endereco.cidade} - ${endereco.estado}
                    </option>
                `).join('')}
            </select>
        `;
    
        console.log('Endereços carregados:', document.getElementById('enderecosSelect'));
    };

    window.alterarQuantidade = async (codigoItem, quantidade) => {
        console.log(`Alterando item ${codigoItem} com quantidade ${quantidade}`);
        const response = await fetch('../../controllers/carrinhoController.php?action=alterarQuantidade', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo_item: codigoItem, quantidade: quantidade })
        });
    
        if (!response.ok) {
            console.error('Erro ao alterar quantidade:', await response.text());
        }
    
        carregarCarrinho();
    };

    window.removerItem = async (codigoItem) => {
        await fetch(`../../controllers/carrinhoController.php?action=remover&codigo_item=${codigoItem}`, { method: 'POST' });
        carregarCarrinho();
    };

    limparCarrinhoBtn.addEventListener('click', async () => {
        await fetch('../../controllers/carrinhoController.php?action=limpar', { method: 'POST' });
        carregarCarrinho();
    });

    finalizarPedidoBtn.addEventListener('click', async () => {
        const enderecoSelecionado = document.getElementById('enderecos');

        if (!enderecoSelecionado || !enderecoSelecionado.value) {
            alert('Selecione um endereço antes de finalizar o pedido.');
            return;
        }

        const response = await fetch('../../controllers/carrinhoController.php?action=finalizar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo_endereco: enderecoSelecionado.value })
        });

        if (response.ok) {
            alert('Pedido finalizado com sucesso!');
            carregarCarrinho();
        } else {
            alert('Erro ao finalizar o pedido.');
        }
    });

    carregarCarrinho();
    carregarEnderecos();
});
