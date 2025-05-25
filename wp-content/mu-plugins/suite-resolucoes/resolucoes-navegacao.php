<?php
/**
 * Sistema de Navegação Personalizada para Resoluções
 * Corrigido: Alinhamento perfeito com o container do conteúdo
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class ResolucaoNavegacao {
    
    public function __construct() {
        add_filter('the_content', array($this, 'adicionar_navegacao'), 10);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        // Fix para barra de administração
        add_action('admin_bar_menu', array($this, 'fix_admin_bar'), 999);
    }
    
    /**
     * Fix para barra de administração em CPT
     */
    public function fix_admin_bar($wp_admin_bar) {
        global $post;
        
        if (is_singular('resolucoes') && $post) {
            // Remove nós problemáticos se existirem
            $wp_admin_bar->remove_node('edit');
            
            // Adiciona novamente o link de edição correto
            $wp_admin_bar->add_node(array(
                'id'    => 'edit',
                'title' => 'Editar Resolução',
                'href'  => get_edit_post_link($post->ID),
                'meta'  => array(
                    'title' => 'Editar esta resolução'
                )
            ));
        }
    }
    
    /**
     * Enqueue CSS para navegação
     */
    public function enqueue_styles() {
        if (is_singular('resolucoes')) {
            $css = "
            /* Navegação personalizada - ALINHAMENTO CORRIGIDO */
            .resolucao-navegacao {
                max-width: 50rem !important;
                margin: 0 auto 2rem auto !important;
                padding: 0 1.5rem !important; /* Mesmo padding do .entry-content */
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                box-sizing: border-box !important;
            }
            
            /* Responsividade - EXATAMENTE igual ao .entry-content */
            @media (max-width: 768px) {
                .resolucao-navegacao {
                    padding: 0 1rem !important; /* Tablet - igual entry-content */
                }
            }
            
            @media (max-width: 480px) {
                .resolucao-navegacao {
                    padding: 0 0.75rem !important; /* Mobile - igual entry-content */
                }
            }
            
            .resolucao-navegacao .nav-btn {
                background: #333 !important;
                color: white !important;
                border: none !important;
                padding: 10px 15px !important;
                cursor: pointer !important;
                border-radius: 4px !important;
                text-decoration: none !important;
                font-size: 16px !important;
                transition: background-color 0.3s ease !important;
                display: inline-block !important;
                min-width: 45px !important;
                text-align: center !important;
            }
            
            .resolucao-navegacao .nav-btn:hover {
                background: #555 !important;
                color: white !important;
                text-decoration: none !important;
            }
            
            .resolucao-navegacao .nav-btn:disabled,
            .resolucao-navegacao .nav-btn.disabled {
                background: #ccc !important;
                cursor: not-allowed !important;
                opacity: 0.6 !important;
            }
            
            .resolucao-navegacao .nav-spacer {
                flex: 1 !important;
            }
            /* Navegação personalizada - SEM PADDING DUPLO */
            .resolucao-navegacao {
                max-width: none !important; /* Remove limitação de largura */
                margin: 0 0 2rem 0 !important; /* Remove centralização automática */
                padding: 0 !important; /* REMOVE TODO O PADDING - já está dentro do entry-content */
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                box-sizing: border-box !important;
            }
            
            /* Remove todos os paddings responsivos também */
            @media (max-width: 768px) {
                .resolucao-navegacao {
                    padding: 0 !important;
                }
            }
            
            @media (max-width: 480px) {
                .resolucao-navegacao {
                    padding: 0 !important;
                }
            }";
            
            wp_add_inline_style('wp-block-library', $css);
        }
    }
    
    /**
     * Adiciona navegação ao conteúdo
     */
    public function adicionar_navegacao($content) {
        if (!is_singular('resolucoes')) {
            return $content;
        }
        
        global $post;
        if (!$post) {
            return $content;
        }
        
        $navegacao = $this->gerar_navegacao($post);
        
        if ($navegacao) {
            return $navegacao . $content;
        }
        
        return $content;
    }
    
    /**
     * Gera HTML da navegação
     */
    private function gerar_navegacao($post_atual) {
        $cache_key = 'resolucao_nav_' . $post_atual->ID;
        $navegacao = get_transient($cache_key);
        
        if ($navegacao !== false) {
            return $navegacao;
        }
        
        $anterior = $this->buscar_post_anterior($post_atual);
        $proximo = $this->buscar_post_proximo($post_atual);
        
        $html = '<div class="resolucao-navegacao">';
        
        // Botão anterior
        if ($anterior) {
            $html .= '<a href="' . get_permalink($anterior->ID) . '" class="nav-btn" title="Questão anterior">←</a>';
        } else {
            $html .= '<span class="nav-btn disabled">←</span>';
        }
        
        // Espaçador
        $html .= '<div class="nav-spacer"></div>';
        
        // Botão próximo
        if ($proximo) {
            $html .= '<a href="' . get_permalink($proximo->ID) . '" class="nav-btn" title="Próxima questão">→</a>';
        } else {
            $html .= '<span class="nav-btn disabled">→</span>';
        }
        
        $html .= '</div>';
        
        // Cache por 1 hora
        set_transient($cache_key, $html, HOUR_IN_SECONDS);
        
        return $html;
    }
    
    /**
     * Busca post anterior na hierarquia
     */
    private function buscar_post_anterior($post_atual) {
        $dados_atual = $this->extrair_dados_slug($post_atual->post_name);
        if (!$dados_atual) return null;
        
        // Busca todas as resoluções
        $query = new WP_Query(array(
            'post_type' => 'resolucoes',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        if (!$query->have_posts()) return null;
        
        $posts_ordenados = $this->ordenar_posts_hierarquicamente($query->posts);
        $indice_atual = array_search($post_atual->ID, $posts_ordenados);
        
        if ($indice_atual === false || $indice_atual === 0) return null;
        
        return get_post($posts_ordenados[$indice_atual - 1]);
    }
    
    /**
     * Busca próximo post na hierarquia
     */
    private function buscar_post_proximo($post_atual) {
        $dados_atual = $this->extrair_dados_slug($post_atual->post_name);
        if (!$dados_atual) return null;
        
        // Busca todas as resoluções
        $query = new WP_Query(array(
            'post_type' => 'resolucoes',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        if (!$query->have_posts()) return null;
        
        $posts_ordenados = $this->ordenar_posts_hierarquicamente($query->posts);
        $indice_atual = array_search($post_atual->ID, $posts_ordenados);
        
        if ($indice_atual === false || $indice_atual === count($posts_ordenados) - 1) return null;
        
        return get_post($posts_ordenados[$indice_atual + 1]);
    }
    
    /**
     * Ordena posts hierarquicamente
     */
    private function ordenar_posts_hierarquicamente($post_ids) {
        $posts_com_dados = array();
        
        foreach ($post_ids as $id) {
            $post = get_post($id);
            $dados = $this->extrair_dados_slug($post->post_name);
            
            if ($dados) {
                $posts_com_dados[] = array(
                    'id' => $id,
                    'vestibular' => $dados['vestibular'],
                    'ano' => intval($dados['ano']),
                    'disciplina' => $dados['disciplina'],
                    'numero' => intval($dados['numero'])
                );
            }
        }
        
        // Ordenação hierárquica
        usort($posts_com_dados, function($a, $b) {
            // 1. Vestibular (alfabética)
            $cmp = strcmp($a['vestibular'], $b['vestibular']);
            if ($cmp !== 0) return $cmp;
            
            // 2. Ano (decrescente)
            $cmp = $b['ano'] - $a['ano'];
            if ($cmp !== 0) return $cmp;
            
            // 3. Disciplina (alfabética)
            $cmp = strcmp($a['disciplina'], $b['disciplina']);
            if ($cmp !== 0) return $cmp;
            
            // 4. Número (crescente)
            return $a['numero'] - $b['numero'];
        });
        
        // Retorna apenas os IDs ordenados
        return array_column($posts_com_dados, 'id');
    }
    
    /**
     * Extrai dados do slug
     */
    private function extrair_dados_slug($slug) {
        // Padrão: vestibular-ano-disciplina-numero
        if (preg_match('/^(.+)-(\d{4})-(.+)-(\d+)$/', $slug, $matches)) {
            return array(
                'vestibular' => $matches[1],
                'ano' => $matches[2],
                'disciplina' => $matches[3],
                'numero' => $matches[4]
            );
        }
        
        return null;
    }
}

// Limpa cache quando posts são salvos/deletados
add_action('save_post', function($post_id) {
    if (get_post_type($post_id) === 'resolucoes') {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_resolucao_nav_%'");
    }
});

add_action('delete_post', function($post_id) {
    if (get_post_type($post_id) === 'resolucoes') {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_resolucao_nav_%'");
    }
});

// Inicializa a classe
new ResolucaoNavegacao();