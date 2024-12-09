document.addEventListener('DOMContentLoaded', () => {
    const produtosContainer = document.getElementById('produtos');

    const carregarProdutos = async () => {
        const response = await fetch('../../controllers/produtoController.php?action=listar');
        const produtos = await response.json();
        produtosContainer.innerHTML = produtos.map(produto => `
            <div class="product transition-transform h-[240px] p-4 justify-between bg-white rounded-xl shadow-xl flex flex-col items-center">
                <img src="../../assets/images/${produto.imagem}" class="w-full h-[140px] rounded" alt="${produto.nome}">
                <div class="product-info w-full relative">
                    <h3 class="text-md">${produto.nome}</h3>
                    <h2 class="price text-md font-bold">R$ ${produto.preco}</h2>
                    <button class="button-add-item-to-bag absolute bottom-2 right-0 h-10 w-10 rounded-full bg-color-secondary hover:bg-color-primary text-white transition-all" onclick="adicionarAoCarrinho(${produto.codigo_produto})">
                        <i class='bx bx-shopping-bag text-xl'></i>
                    </button>
                </div>
            </div>
        `).join('');
    };

    const contarItensCarrinho = async () => {
        const response = await fetch('../../controllers/carrinhoController.php?action=contar');
        const total_itens = await response.json();
        document.getElementById('contagemItens').innerHTML = total_itens;
    };

    window.adicionarAoCarrinho = async (codigoProduto) => {
        await fetch('../../controllers/carrinhoController.php?action=adicionar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo_produto: codigoProduto })
        });
        alert('Produto adicionado ao carrinho!');
        contarItensCarrinho();
    };

    carregarProdutos();
    contarItensCarrinho();
});
