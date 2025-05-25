<?php
/**
 * Custom Post Type: Resoluções
 * Funcionalidade básica - apenas o CPT e taxonomias
 */

// Evita acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Registra o Custom Post Type
function criar_cpt_resolucoes() {
    $labels = array(
        'name'                  => 'Resoluções',
        'singular_name'         => 'Resolução',
        'menu_name'             => 'Resoluções',
        'name_admin_bar'        => 'Resolução',
        'archives'              => 'Arquivo de Resoluções',
        'attributes'            => 'Atributos da Resolução',
        'parent_item_colon'     => 'Resolução Principal:',
        'all_items'             => 'Todas as Resoluções',
        'add_new_item'          => 'Adicionar Nova Resolução',
        'add_new'               => 'Adicionar Nova',
        'new_item'              => 'Nova Resolução',
        'edit_item'             => 'Editar Resolução',
        'update_item'           => 'Atualizar Resolução',
        'view_item'             => 'Ver Resolução',
        'view_items'            => 'Ver Resoluções',
        'search_items'          => 'Buscar Resoluções',
        'not_found'             => 'Nenhuma resolução encontrada',
        'not_found_in_trash'    => 'Nenhuma resolução encontrada na lixeira',
        'featured_image'        => 'Imagem da Questão',
        'set_featured_image'    => 'Definir imagem da questão',
        'remove_featured_image' => 'Remover imagem da questão',
        'use_featured_image'    => 'Usar como imagem da questão',
        'insert_into_item'      => 'Inserir na resolução',
        'uploaded_to_this_item' => 'Enviado para esta resolução',
        'items_list'            => 'Lista de resoluções',
        'items_list_navigation' => 'Navegação da lista de resoluções',
        'filter_items_list'     => 'Filtrar lista de resoluções',
    );

    $args = array(
        'label'                 => 'Resolução',
        'description'           => 'Exercícios de Enem e vestibulares com resoluções',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Habilita o editor Gutenberg
    );

    register_post_type('resolucoes', $args);
}

// Registra o Custom Post Type no hook init
add_action('init', 'criar_cpt_resolucoes', 0);

// Força a atualização dos permalinks quando o plugin é ativado
function resolucoes_rewrite_flush() {
    criar_cpt_resolucoes();
    flush_rewrite_rules();
}

// Executa a função quando o WordPress carrega
add_action('wp_loaded', 'resolucoes_rewrite_flush');

// Registra Taxonomias Personalizadas
function criar_taxonomias_resolucoes() {
    
    // Taxonomia: Vestibular
    $vestibular_labels = array(
        'name'              => 'Vestibulares',
        'singular_name'     => 'Vestibular',
        'search_items'      => 'Buscar Vestibulares',
        'all_items'         => 'Todos os Vestibulares',
        'parent_item'       => 'Vestibular Principal',
        'parent_item_colon' => 'Vestibular Principal:',
        'edit_item'         => 'Editar Vestibular',
        'update_item'       => 'Atualizar Vestibular',
        'add_new_item'      => 'Adicionar Novo Vestibular',
        'new_item_name'     => 'Nome do Novo Vestibular',
        'menu_name'         => 'Vestibulares',
    );

    register_taxonomy('vestibular', array('resolucoes'), array(
        'hierarchical'      => true,
        'labels'            => $vestibular_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'vestibular'),
        'show_in_rest'      => true,
    ));

    // Taxonomia: Ano
    $ano_labels = array(
        'name'              => 'Anos',
        'singular_name'     => 'Ano',
        'search_items'      => 'Buscar Anos',
        'all_items'         => 'Todos os Anos',
        'parent_item'       => 'Ano Principal',
        'parent_item_colon' => 'Ano Principal:',
        'edit_item'         => 'Editar Ano',
        'update_item'       => 'Atualizar Ano',
        'add_new_item'      => 'Adicionar Novo Ano',
        'new_item_name'     => 'Nome do Novo Ano',
        'menu_name'         => 'Anos',
    );

    register_taxonomy('ano', array('resolucoes'), array(
        'hierarchical'      => true,
        'labels'            => $ano_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ano'),
        'show_in_rest'      => true,
    ));

    // Taxonomia: Disciplina
    $disciplina_labels = array(
        'name'              => 'Disciplinas',
        'singular_name'     => 'Disciplina',
        'search_items'      => 'Buscar Disciplinas',
        'all_items'         => 'Todas as Disciplinas',
        'parent_item'       => 'Disciplina Principal',
        'parent_item_colon' => 'Disciplina Principal:',
        'edit_item'         => 'Editar Disciplina',
        'update_item'       => 'Atualizar Disciplina',
        'add_new_item'      => 'Adicionar Nova Disciplina',
        'new_item_name'     => 'Nome da Nova Disciplina',
        'menu_name'         => 'Disciplinas',
    );

    register_taxonomy('disciplina', array('resolucoes'), array(
        'hierarchical'      => true,
        'labels'            => $disciplina_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'disciplina'),
        'show_in_rest'      => true,
    ));
}

// Registra as taxonomias
add_action('init', 'criar_taxonomias_resolucoes', 0);

?>