# =========================================================
# Desabilita a listagem de diretórios (Options -Indexes)
# Atende ao requisito: "Proibir listagem de diretórios"
#
# Sem isso, se alguém acessar uma pasta do site que não
# tenha um index.php (ex: http://localhost:8080/img/),
# o Apache mostraria a lista de todos os arquivos.
# =========================================================
Options -Indexes

# (Opcional) Esconde o próprio .htaccess de ser acessado via navegador
<Files .htaccess>
    Require all denied
</Files>
