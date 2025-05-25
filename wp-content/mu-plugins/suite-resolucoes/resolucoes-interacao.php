<?php
/**
 * Sistema de Interação para Resoluções
 * Funcionalidade: Seleção de alternativas, correção e explicações
 * VERSÃO CORRIGIDA - Caminho do JS
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class ResolucaoInteracao {
    
    public function __construct() {
        // Debug
        error_log('ResolucaoInteracao: Classe inicializada');
        
        // Hooks principais
        add_action('add_meta_boxes', array($this, 'adicionar_meta_box'));
        add_action('save_post', array($this, 'salvar_resposta_correta'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'adicionar_dados_javascript'));
    }
    
    /**
     * Adiciona meta box no admin para definir resposta correta
     */
    public function adicionar_meta_box() {
        add_meta_box(
            'resolucao_resposta_correta',
            'Configuração da Questão',
            array($this, 'meta_box_callback'),
            'resolucoes',
            'side',
            'high'
        );
    }
    
    /**
     * Callback do meta box - interface no admin
     */
    public function meta_box_callback($post) {
        // Nonce para segurança
        wp_nonce_field('resolucao_resposta_nonce', 'resolucao_resposta_nonce_field');
        
        // Valores atuais
        $resposta_correta = get_post_meta($post->ID, '_resolucao_resposta_correta', true);
        $explicacao = get_post_meta($post->ID, '_resolucao_explicacao', true);
        
        ?>
        <table class="form-table">
            <tr>
                <td>
                    <label for="resolucao_resposta_correta"><strong>Resposta Correta:</strong></label>
                </td>
            </tr>
            <tr>
                <td>
                    <select name="resolucao_resposta_correta" id="resolucao_resposta_correta" style="width: 100%;">
                        <option value="">-- Selecione --</option>
                        <option value="A" <?php selected($resposta_correta, 'A'); ?>>A</option>
                        <option value="B" <?php selected($resposta_correta, 'B'); ?>>B</option>
                        <option value="C" <?php selected($resposta_correta, 'C'); ?>>C</option>
                        <option value="D" <?php selected($resposta_correta, 'D'); ?>>D</option>
                        <option value="E" <?php selected($resposta_correta, 'E'); ?>>E</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td style="padding-top: 15px;">
                    <label for="resolucao_explicacao"><strong>Explicação da Resposta:</strong></label>
                </td>
            </tr>
            <tr>
                <td>
                    <textarea 
                        name="resolucao_explicacao" 
                        id="resolucao_explicacao" 
                        rows="4" 
                        style="width: 100%;"
                        placeholder="Explicação detalhada + link da videoaula..."
                    ><?php echo esc_textarea($explicacao); ?></textarea>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 10px; padding: 10px; background: #f0f0f1; border-radius: 4px;">
            <small>
                <strong>Dica:</strong> A explicação pode incluir texto e HTML (para vídeos, links, etc.)
            </small>
        </div>
        <?php
    }
    
    /**
     * Salva os dados do meta box
     */
    public function salvar_resposta_correta($post_id) {
        // Verificações de segurança
        if (!isset($_POST['resolucao_resposta_nonce_field'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['resolucao_resposta_nonce_field'], 'resolucao_resposta_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Salva resposta correta
        if (isset($_POST['resolucao_resposta_correta'])) {
            $resposta = sanitize_text_field($_POST['resolucao_resposta_correta']);
            update_post_meta($post_id, '_resolucao_resposta_correta', $resposta);
        }
        
        // Salva explicação
        if (isset($_POST['resolucao_explicacao'])) {
            $explicacao = wp_kses_post($_POST['resolucao_explicacao']);
            update_post_meta($post_id, '_resolucao_explicacao', $explicacao);
        }
    }
    
    /**
     * Enfileira scripts e estilos apenas em páginas de resolução
     */
    public function enqueue_scripts() {
        if (!is_singular('resolucoes')) {
            return;
        }
        
        // CORREÇÃO DEFINITIVA: Caminho absoluto baseado na estrutura real
        // O arquivo está em: wp-content/mu-plugins/suite-resolucoes/assets/js/
        $js_url = content_url('mu-plugins/suite-resolucoes/assets/js/resolucoes-interacao.js');
        
        // Debug do caminho
        error_log('JavaScript URL Final: ' . $js_url);
        
        // JavaScript da interação
        wp_enqueue_script(
            'resolucoes-interacao',
            $js_url,
            array(),
            '1.2.' . time(), // Força reload com timestamp
            true
        );
        
        // Debug adicional
        error_log('Script enfileirado: resolucoes-interacao com URL: ' . $js_url);
    }
    
    /**
     * Adiciona dados necessários para o JavaScript
     */
    public function adicionar_dados_javascript() {
        if (!is_singular('resolucoes')) {
            return;
        }
        
        global $post;
        
        $resposta_correta = get_post_meta($post->ID, '_resolucao_resposta_correta', true);
        $explicacao = get_post_meta($post->ID, '_resolucao_explicacao', true);
        
        // Debug dos meta fields
        error_log('Post ID: ' . $post->ID);
        error_log('Resposta Correta: ' . $resposta_correta);
        error_log('Explicação: ' . substr($explicacao, 0, 50) . '...');
        
        // Só adiciona se houver resposta configurada
        if (empty($resposta_correta)) {
            error_log('Resposta correta vazia - sistema não ativado');
            return;
        }
        
        error_log('Adicionando configuração JavaScript');
        
        ?>
        <script type="text/javascript">
        window.resolucaoConfig = {
            postId: <?php echo $post->ID; ?>,
            respostaCorreta: '<?php echo esc_js($resposta_correta); ?>',
            explicacao: <?php echo json_encode($explicacao); ?>,
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('resolucao_interacao_nonce'); ?>'
        };
        console.log('🔧 DEBUG: resolucaoConfig definido', window.resolucaoConfig);
        </script>
        <?php
    }
    
    /**
     * Verifica se a questão tem interação configurada
     */
    public static function tem_interacao($post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        $resposta_correta = get_post_meta($post_id, '_resolucao_resposta_correta', true);
        return !empty($resposta_correta);
    }
}

// Inicializa a classe
new ResolucaoInteracao();