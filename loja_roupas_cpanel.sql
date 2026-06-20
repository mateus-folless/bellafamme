<?php
/**
 * Funções auxiliares utilizadas em várias páginas do site.
 * Seguem o padrão: recebem parâmetros e retornam um valor (sem usar variáveis globais).
 */

/**
 * Formata um valor numérico para o formato monetário brasileiro.
 * Ex: 159.9 -> "R$ 159,90"
 */
function formatarPreco(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Retorna a saudação adequada de acordo com a hora do dia.
 * Demonstra uso de IF / ELSEIF / ELSE.
 */
function saudacaoPorHorario(int $hora): string
{
    if ($hora >= 5 && $hora < 12) {
        return 'Bom dia';
    } elseif ($hora >= 12 && $hora < 18) {
        return 'Boa tarde';
    } else {
        return 'Boa noite';
    }
}

/**
 * Retorna informações de exibição do estoque (texto e classe do badge),
 * dependendo da quantidade disponível.
 */
function statusEstoque(int $quantidade): array
{
    if ($quantidade <= 0) {
        return ['texto' => 'Esgotado', 'classe' => 'badge-esgotado'];
    } elseif ($quantidade <= 5) {
        return ['texto' => 'Últimas unidades', 'classe' => 'badge-categoria'];
    } else {
        return ['texto' => 'Disponível', 'classe' => 'badge-categoria'];
    }
}
