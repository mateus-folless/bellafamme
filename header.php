<?php
// Exibe o ano atual dinamicamente (PHP) no rodapé
$anoAtual = date('Y');
?>
<footer class="rodape">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="fonte-titulo">BELLA FEMME</h5>
                <p class="mb-0">Moda feminina com elegância, conforto e atitude. Peças selecionadas para todos os estilos.</p>
            </div>
            <div class="col-md-4">
                <h5 class="fonte-titulo">Navegação</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php">Início</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="tech_forge.php">Tech Forge</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="fonte-titulo">Contato</h5>
                <ul class="list-unstyled">
                    <li><i class="bi bi-geo-alt"></i> Peabiru - PR</li>
                    <li><i class="bi bi-envelope"></i> contato@bellafemme.com.br</li>
                    <li><i class="bi bi-phone"></i> (44) 99999-0000</li>
                </ul>
            </div>
        </div>
        <div class="copy">
            &copy; <?php echo $anoAtual; ?> Bella Femme — Projeto acadêmico (Tech Academy).
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
