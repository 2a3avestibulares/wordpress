<?php
/**
 * Suite Resoluções - Versão Final v1.1
 * Sistema completo para gerenciar resoluções de vestibulares
 * Novo: Sistema de interação com alternativas
 */

if (!defined('ABSPATH')) {
    exit;
}

$pasta_suite = __DIR__ . '/suite-resolucoes/';

// Módulos principais (sempre carregados)
require_once $pasta_suite . 'resolucoes-cpt.php';
require_once $pasta_suite . 'resolucoes-admin.php';
require_once $pasta_suite . 'resolucoes-navegacao.php';

// Novo: Sistema de interação
require_once $pasta_suite . 'resolucoes-interacao.php';

// Módulos opcionais (carregados após inicialização)
add_action('init', function() use ($pasta_suite) {
    // Estilos e formatação
    require_once $pasta_suite . 'resolucoes-estilos.php';
    
    // Bloco personalizado de referência
    require_once $pasta_suite . 'resolucoes-referencia.php';
});