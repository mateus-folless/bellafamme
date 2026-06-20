<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$tituloPagina = 'Produtos';
$paginaAtual  = 'produtos';

// ---------------------------------------------------------
// Filtros recebidos via GET
// ---------------------------------------------------------
$categoriaSelecionada = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;
$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

// Busca todas as categorias (para o menu de filtro)
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();

// ---------------------------------------------------------
// Monta a consulta SQL de acordo com os filtros (IF / ELSE)
// ---------------------------------------------------------
$sql = "SELECT p.*, c.nome AS nome_categoria
        FROM produtos p
        INNER JOIN categorias c ON c.id_categoria = p.id_categoria
        WHERE 1 = 1";

$parametros = [];

if ($categoriaSelecionada > 0) {
    $sql .= " AND p.id_categoria = :categoria";
    $parametros['categoria'] = $categoriaSelecionada;
}

if ($termoBusca !== '') {
    $sql .= " AND p.nome LIKE :busca";
    $parametros['busca'] = '%' . $termoBusca . '%';
}

$sql .= " ORDER BY p.nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

require_once 'includes/header.php';
?>

<div class="container py-4">

    <div class="titulo-secao">
        <h2 class="fonte-titulo">Nossos Produtos</h2>
    </div>

    <!-- ===================== FORMULÁRIO DE FILTRO ===================== -->
    <form method="GET" action="produtos.php" class="row g-3 mb-4 align-items-end">
        <div class="col-md-5">
            <label for="busca" class="form-label">Buscar por nome</label>
            <input type="text" class="form-control" id="busca" name="busca"
                   placeholder="Ex: vestido, blusa, saia..."
                   value="<?php echo htmlspecialchars($termoBusca); ?>">
        </div>
        <div class="col-md-4">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="0">Todas as categorias</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo (int) $categoria['id_categoria']; ?>"
                        <?php echo $categoriaSelecionada === (int) $categoria['id_categoria'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn" style="background-color: var(--verde-musgo); color: var(--creme);">
                <i class="bi bi-search"></i> Filtrar
            </button>
            <a href="produtos.php" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </form>

    <!-- ===================== RESULTADOS ===================== -->
    <div class="row g-4">
        <?php
        $totalEncontrados = 0;

        // WHILE percorrendo o resultado da consulta
        while ($produto = $stmt->fetch()) {
            $totalEncontrados++;
            $estoque = statusEstoque((int) $produto['estoque']);
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-produto">
                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <div class="card-body">
                        <span class="badge <?php echo $estoque['classe']; ?> mb-2"><?php echo $estoque['texto']; ?></span>
                        <h3 class="card-title fonte-titulo"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="text-muted small mb-1">
                            <?php echo htmlspecialchars($produto['nome_categoria']); ?> · Tam: <?php echo htmlspecialchars($produto['tamanho']); ?>
                        </p>
                        <p class="small mb-2"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p class="preco mb-0"><?php echo formatarPreco((float) $produto['preco']); ?></p>
                    </div>
                </div>
            </div>
        <?php
        }

        // IF / ELSE: mensagem quando nada é encontrado
        if ($totalEncontrados === 0) {
        ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Nenhum produto encontrado para os filtros selecionados.
                </div>
            </div>
        <?php
        }
        ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
