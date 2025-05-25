<?php
/**
 * Funcionalidades Administrativas para Resoluções
 * Duplicação de posts e outras funcionalidades do admin
 */

// Evita acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Funcionalidade de Duplicação para Resoluções
function adicionar_link_duplicar_resolucao($actions, $post) {
    if ($post->post_type == 'resolucoes') {
        $actions['duplicate'] = '<a href="' . wp_nonce_url(admin_url('admin.php?action=duplicar_resolucao&post=' . $post->ID), 'duplicar_resolucao_' . $post->ID) . '">Duplicar</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'adicionar_link_duplicar_resolucao', 10, 2);

// Função que executa a duplicação
function executar_duplicacao_resolucao() {
    if (!isset($_GET['post']) || !isset($_GET['action']) || $_GET['action'] !== 'duplicar_resolucao') {
        return;
    }

    $post_id = intval($_GET['post']);
    
    // Verifica nonce para segurança
    if (!wp_verify_nonce($_GET['_wpnonce'], 'duplicar_resolucao_' . $post_id)) {
        wp_die('Erro de segurança. Tente novamente.');
    }

    // Verifica permissões
    if (!current_user_can('edit_posts')) {
        wp_die('Você não tem permissão para fazer isso.');
    }

    // Busca o post original
    $post_original = get_post($post_id);
    
    if (!$post_original || $post_original->post_type !== 'resolucoes') {
        wp_die('Post não encontrado ou tipo inválido.');
    }

    // Cria o novo post
    $novo_post = array(
        'post_title'    => $post_original->post_title . ' (Cópia)',
        'post_content'  => $post_original->post_content,
        'post_excerpt'  => $post_original->post_excerpt,
        'post_status'   => 'draft', // Cria como rascunho
        'post_type'     => 'resolucoes',
        'post_author'   => get_current_user_id(),
    );

    $novo_post_id = wp_insert_post($novo_post);

    if ($novo_post_id) {
        // Copia as taxonomias (Vestibular, Ano, Disciplina)
        $taxonomias = array('vestibular', 'ano', 'disciplina');
        foreach ($taxonomias as $taxonomia) {
            $termos = wp_get_post_terms($post_id, $taxonomia, array('fields' => 'ids'));
            if (!is_wp_error($termos)) {
                wp_set_post_terms($novo_post_id, $termos, $taxonomia);
            }
        }

        // Copia a imagem destacada se existir
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if ($thumbnail_id) {
            set_post_thumbnail($novo_post_id, $thumbnail_id);
        }

        // Copia custom fields se existirem
        $custom_fields = get_post_custom($post_id);
        foreach ($custom_fields as $key => $values) {
            if (substr($key, 0, 1) !== '_') { // Ignora campos internos do WordPress
                foreach ($values as $value) {
                    add_post_meta($novo_post_id, $key, $value);
                }
            }
        }

        // Redireciona para editar o novo post
        wp_redirect(admin_url('post.php?action=edit&post=' . $novo_post_id));
        exit;
    } else {
        wp_die('Erro ao criar a cópia. Tente novamente.');
    }
}
add_action('admin_action_duplicar_resolucao', 'executar_duplicacao_resolucao');

?>