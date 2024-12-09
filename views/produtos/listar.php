<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDI - Produtos</title>
    <script src="../../assets/js/produtos.js" defer></script>
    
    <!-- script-tailwind-css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../tailwind.config.js"></script>

    <!-- link-bx-icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- link-css -->
    <link rel="stylesheet" href="../../assets/styles/global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>

<body class="font-sans overflow-x-hidden scroll-smooth transition-all">

  <header class="header border border-color-secondary">
    <div class="header-container flex items-center justify-between px-14">

      <section class="section-logo-header-container">
        <img src="../../assets/images/logo.svg" alt="Logo Padoca Dona Inês" class="h-[5rem]">
      </section>

      <seaction class="section-assets-header-container flex gap-4">
        <button class="button-bag bg-color-light rounded-full w-12 h-12 flex items-center justify-center relative">
          <span class="bag-items-text absolute top-0 right-0 bg-color-primary text-white rounded-full w-6 h-6 flex justify-center items-center" id="contagemItens"></span>
            <a href="../carrinho/listar.php"><i class='bx bx-shopping-bag text-color-secondary text-xl'></i></a>
        </button>
        <button class="button-login bg-color-light rounded-full w-12 h-12 flex items-center justify-center">
          <a href="perfil.html"><i class='bx bx-user text-color-secondary text-xl'></i></a>
        </button>
        <button class="button-login border rounded-full w-12 h-12 flex items-center justify-center transition-all border-color-secondary text-color-secondary hover:border-red-600 hover:text-red-600">
            <a href="logout.html"><i class='bx bx-log-out text-xl rotate-180 pr-1 pb-0.5'></i></a>
        </button>
      </seaction>

    </div>
  </header>

  <main class="h-full bg-red-100 bg-[url('../../assets/images/logo-background.png')] bg-no-repeat bg-contain">

    <div class="container px-14 p-6">

          <h1 class="text-2xl font-bold mb-6 mt-2 text-color-primary">Produtos Disponíveis</h1>

          <div id="produtos" class="grid grid-cols-5 gap-6"><!-- Produtos serão carregados aqui --></div>

    </div>

  </main>
  
</body>

</html>
