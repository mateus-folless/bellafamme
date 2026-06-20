<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$tituloPagina = 'Início';
$paginaAtual  = 'index';

// Saudação dinâmica baseada no horário (PHP dinâmico)
$horaAtual = (int) date('H');
$saudacao  = saudacaoPorHorario($horaAtual);

// Busca produtos em destaque (os 4 primeiros cadastrados)
$stmtDestaques = $pdo->query("
    SELECT p.*, c.nome AS nome_categoria
    FROM produtos p
    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
    ORDER BY p.id_produto ASC
    LIMIT 4
");
$produtosDestaque = $stmtDestaques->fetchAll();

// Busca todas as categorias para a seção de categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();

require_once 'includes/header.php';
?>

<!-- ===================== HERO / CARROSSEL ===================== -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <p class="text-uppercase mb-2" style="letter-spacing:3px; color: var(--dourado);">
                    <?php echo htmlspecialchars($saudacao); ?>, seja bem-vinda(o)!
                </p>
                <h1 class="fonte-titulo mb-3">Nova Coleção <br>para você</h1>
                <p class="lead mb-4">
                    Peças exclusivas, conforto no dia a dia e elegância em cada detalhe.
                    Confira nossa seleção de vestidos, blusas, calças e acessórios.
                </p>
                <a href="produtos.php" class="btn btn-dourado">Ver Produtos</a>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <!-- Componente Bootstrap: Carousel -->
                <div id="carrosselDestaques" class="carousel slide rounded shadow" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <?php foreach ($produtosDestaque as $indice => $produto): ?>
                            <div class="carousel-item <?php echo $indice === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                     class="d-block w-100"
                                     style="height:320px; object-fit:cover;"
                                     alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?php echo htmlspecialchars($produto['nome']); ?></h5>
                                    <p><?php echo formatarPreco((float) $produto['preco']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carrosselDestaques" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carrosselDestaques" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">

    <!-- ===================== PRODUTOS EM DESTAQUE ===================== -->
    <div class="titulo-secao">
        <h2 class="fonte-titulo">Produtos em Destaque</h2>
    </div>

    <div class="row g-4 mb-5">
        <?php
        // FOREACH percorrendo os produtos retornados do banco
        foreach ($produtosDestaque as $produto):
            $estoque = statusEstoque((int) $produto['estoque']);
        ?>
            <div class="col-6 col-md-3">
                <!-- Componente Bootstrap: Card -->
                <div class="card card-produto">
                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <div class="card-body">
                        <span class="badge <?php echo $estoque['classe']; ?> mb-2"><?php echo $estoque['texto']; ?></span>
                        <h3 class="card-title fonte-titulo"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($produto['nome_categoria']); ?></p>
                        <p class="preco mb-0"><?php echo formatarPreco((float) $produto['preco']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===================== CATEGORIAS ===================== -->
    <div class="titulo-secao">
        <h2 class="fonte-titulo">Navegue por Categoria</h2>
    </div>

    <div class="row g-3 mb-5">
        <?php foreach ($categorias as $categoria): ?>
            <div class="col-6 col-md-2-4" style="flex: 0 0 20%; max-width: 20%;">
                <a href="produtos.php?categoria=<?php echo (int) $categoria['id_categoria']; ?>" class="text-decoration-none">
                    <div class="card card-produto text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-tag fonte-titulo" style="font-size:1.6rem; color: var(--verde-musgo);"></i>
                            <h3 class="card-title fonte-titulo mt-2" style="font-size:1rem;">
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </h3>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
