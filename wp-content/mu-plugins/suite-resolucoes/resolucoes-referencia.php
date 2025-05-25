<?php
/**
 * Resoluções Suite
 * Plugin/Sistema completo para gerenciar resoluções de vestibulares
 * 
 * Version: 1.0
 * Author: Seu Nome
 */

// Evita acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('RESOLUCOES_SUITE_PATH', __DIR__ . '/resolucoes-suite/');
define('RESOLUCOES_SUITE_URL', plugin_dir_url(__FILE__) . 'resolucoes-suite/');

/**
 * Classe principal do Resoluções Suite
 */
class ResolucoesSuite {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        $this->load_dependencies();
    }
    
    /**
     * Carrega todos os arquivos de dependências
     */
    private function load_dependencies() {
        
        // 1. Funcionalidade básica - CPT e Taxonomias (sempre carregado)
        require_once RESOLUCOES_SUITE_PATH . 'resolucoes-cpt.php';
        
        // 2. Bloco Referência (opcional - pode ser desabilitado)
        if (apply_filters('resolucoes_suite_enable_bloco_referencia', true)) {
            require_once RESOLUCOES_SUITE_PATH . 'resolucoes-referencia.php';
        }
        
        // 3. Funcionalidades Admin (opcional - pode ser desabilitado)
        if (apply_filters('resolucoes_suite_enable_admin_features', true)) {
            require_once RESOLUCOES_SUITE_PATH . 'resolucoes-admin.php';
        }
        
        // 4. Estilos e Formatação (opcional - pode ser desabilitado)
        if (apply_filters('resolucoes_suite_enable_custom_styles', true)) {
            require_once RESOLUCOES_SUITE_PATH . 'resolucoes-estilos.php';
        }
        
        // 5. Navegação Personalizada (opcional - pode ser desabilitado)
        if (apply_filters('resolucoes_suite_enable_custom_navigation', true)) {
            require_once RESOLUCOES_SUITE_PATH . 'resolucoes-navegacao.php';
        }
    }
}

// Inicializa o plugin
new ResolucoesSuite();

/**
 * Função para desabilitar módulos específicos
 * Adicione no functions.php do seu tema ou em outro plugin
 */

// Exemplo de como desabilitar módulos:
/*
// Desabilita estilos personalizados
add_filter('resolucoes_suite_enable_custom_styles', '__return_false');

// Desabilita navegação personalizada
add_filter('resolucoes_suite_enable_custom_navigation', '__return_false');

// Desabilita funcionalidades admin
add_filter('resolucoes_suite_enable_admin_features', '__return_false');

// Desabilita bloco referência
add_filter('resolucoes_suite_enable_bloco_referencia', '__return_false');
*/

/**
 * Ativação do plugin
 */
function resolucoes_suite_activation() {
    // Força atualização dos permalinks
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'resolucoes_suite_activation');

/**
 * Desativação do plugin
 */
function resolucoes_suite_deactivation() {
    // Limpa os permalinks
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'resolucoes_suite_deactivation');

?>