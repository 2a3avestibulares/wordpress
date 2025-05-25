<?php
/**
 * Estilos Personalizados para CPT Resoluções
 * Versão 1.2: Sistema de interação corrigido - sem pontos e alinhamento perfeito
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class ResolucaoEstilos {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'adicionar_estilos'));
        add_action('wp_head', array($this, 'css_personalizado'));
    }
    
    /**
     * Enqueue estilos básicos
     */
    public function adicionar_estilos() {
        if (is_singular('resolucoes')) {
            wp_enqueue_style('wp-block-library');
        }
    }
    
    /**
     * CSS personalizado para resoluções
     */
    public function css_personalizado() {
        if (!is_singular('resolucoes')) {
            return;
        }
        
        ?>
        <style type="text/css">
        /* ========================================
           FIX PARA ADMIN BAR - CORRIGIDO
        ======================================== */
        
        /* Remove qualquer margem conflituosa do body */
        body.single-resolucoes.admin-bar {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Garante que a admin bar funcione sem criar espaço extra */
        body.single-resolucoes #wpadminbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 99999 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Remove qualquer elemento fantasma que cause barra branca */
        body.single-resolucoes::before,
        body.single-resolucoes::after,
        body.single-resolucoes .site::before,
        body.single-resolucoes .site::after {
            display: none !important;
            content: none !important;
        }
        
        /* Fix específico para o tema Astra com admin bar */
        body.single-resolucoes.admin-bar .site,
        body.single-resolucoes.admin-bar .site-header,
        body.single-resolucoes.admin-bar .site-content {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Garante que o HTML/body não tenham altura extra */
        html.admin-bar body.single-resolucoes,
        body.single-resolucoes.admin-bar {
            min-height: calc(100vh - 32px) !important;
        }
        
        @media screen and (max-width: 782px) {
            html.admin-bar body.single-resolucoes,
            body.single-resolucoes.admin-bar {
                min-height: calc(100vh - 46px) !important;
            }
        }
        
        /* ========================================
           LAYOUT PERSONALIZADO
        ======================================== */
        
        /* Remove container do tema apenas em resoluções */
        .single-resolucoes .ast-container,
        .single-resolucoes .container,
        .single-resolucoes .site-content > .ast-container {
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Container personalizado invisível */
        .single-resolucoes .entry-content {
            max-width: 50rem !important;
            margin: 0 auto !important;
            padding: 2rem 1.5rem !important;
            box-sizing: border-box !important;
            
            /* Remove qualquer aparência visual do container */
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }
        
        /* ========================================
           RESPONSIVIDADE DO CONTAINER
        ======================================== */
        
        /* Tablet */
        @media (max-width: 768px) {
            .single-resolucoes .entry-content {
                padding: 1.5rem 1rem !important;
            }
        }
        
        /* Mobile */
        @media (max-width: 480px) {
            .single-resolucoes .entry-content {
                padding: 1rem 0.75rem !important;
            }
        }
        
        /* ========================================
           TIPOGRAFIA E ELEMENTOS
        ======================================== */
        
        /* H1 sempre em maiúsculas */
        .single-resolucoes h1 {
            text-transform: uppercase !important;
            font-size: 1rem !important;
            margin-bottom: 1.25rem !important;
            margin-top: 0 !important;
        }
        
        /* Todos os headings com mesmo tamanho */
        .single-resolucoes h1,
        .single-resolucoes h2,
        .single-resolucoes h3,
        .single-resolucoes h4,
        .single-resolucoes h5,
        .single-resolucoes h6 {
            font-size: 1rem !important;
            line-height: 1.4 !important;
            margin-top: 0 !important;
            margin-bottom: 1.25rem !important;
        }
        
        /* Parágrafos justificados */
        .single-resolucoes .entry-content p {
            text-align: justify !important;
            margin-bottom: 1rem !important;
            margin-top: 0 !important;
            line-height: 1.6 !important;
        }
        
        /* ========================================
           LISTAS COM LETRAS A, B, C, D, E - SEM PONTOS
        ======================================== */
        
        .single-resolucoes .entry-content ol,
        .single-resolucoes .entry-content ul {
            counter-reset: item !important;
            list-style: none !important;
            padding-left: 0 !important;
            margin-bottom: 1.25rem !important;
        }
        
        .single-resolucoes .entry-content ol li,
        .single-resolucoes .entry-content ul li {
            counter-increment: item !important;
            padding-left: calc(1rem + 1.5em) !important;
            margin-bottom: 0.25rem !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            min-height: 1.6em !important;
        }
        
        /* REMOVIDO O PONTO: counter(item, upper-alpha) sem "." */
        .single-resolucoes .entry-content ol li::before,
        .single-resolucoes .entry-content ul li::before {
            content: counter(item, upper-alpha) !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            font-family: monospace !important;
            font-weight: bold !important;
            width: 1.5em !important;
            text-align: left !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
        }
        
        /* FORÇA REMOÇÃO DE PONTOS EM TODAS AS SITUAÇÕES */
        .single-resolucoes .entry-content ol li::after,
        .single-resolucoes .entry-content ul li::after,
        .single-resolucoes .resolucao-alternativa::after,
        .single-resolucoes .resolucao-letra-botao::before,
        .single-resolucoes .resolucao-letra-botao::after {
            display: none !important;
            content: none !important;
        }
        
        /* ========================================
           SISTEMA DE INTERAÇÃO - LETRAS COMO BOTÕES
        ======================================== */
        
        /* Container das alternativas com alinhamento perfeito */
        .single-resolucoes .entry-content .resolucao-alternativa {
            position: relative !important;
            padding-left: 3rem !important;
            margin-bottom: 1rem !important;
            min-height: 2.5em !important;
            display: flex !important;
            align-items: center !important; /* CENTRALIZAÇÃO VERTICAL PERFEITA */
            line-height: 1.6 !important;
        }
        
        /* Esconde o ::before quando o JavaScript adiciona a classe */
        .single-resolucoes .entry-content .resolucao-alternativa.tem-botao-js::before {
            display: none !important;
            content: none !important;
        }
        
        /* Mantém o ::before original como fallback - SEM PONTO */
        .single-resolucoes .resolucao-alternativa::before {
            content: counter(item, upper-alpha) !important; /* SEM PONTO */
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            font-family: monospace !important;
            font-weight: bold !important;
            width: 1.5em !important;
            text-align: center !important;
            color: #666 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
        }
        
        /* FORÇA ESCONDER ::before QUANDO TEM BOTÃO JS */
        .single-resolucoes .entry-content .resolucao-alternativa.tem-botao-js::before,
        .single-resolucoes .resolucao-alternativa.tem-botao-js::before {
            display: none !important;
            content: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
        
        /* Botão da letra com posicionamento e alinhamento perfeitos */
        .single-resolucoes .entry-content .resolucao-letra-botao {
            position: absolute !important;
            left: 0 !important;
            top: 50% !important; /* CENTRALIZAÇÃO VERTICAL PERFEITA */
            transform: translateY(-50%) !important; /* CENTRALIZAÇÃO VERTICAL PERFEITA */
            width: 2.5em !important;
            height: 2.5em !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-family: monospace !important;
            font-weight: bold !important;
            font-size: 1rem !important;
            cursor: pointer !important;
            border-radius: 50% !important;
            transition: all 0.3s ease !important;
            background-color: #f5f5f5 !important;
            border: 2px solid #e0e0e0 !important;
            color: #333 !important;
            user-select: none !important;
            z-index: 10 !important;
            box-sizing: border-box !important;
            text-decoration: none !important;
            outline: none !important;
        }
        
        /* Estados com especificidade máxima */
        .single-resolucoes .entry-content .resolucao-letra-botao:hover {
            background-color: #e3f2fd !important;
            border-color: #90caf9 !important;
            transform: translateY(-50%) scale(1.05) !important; /* MANTÉM CENTRALIZAÇÃO NO HOVER */
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2) !important;
        }
        
        /* Estado selecionado */
        .single-resolucoes .entry-content .resolucao-letra-botao.selecionada {
            background-color: #2196f3 !important;
            border-color: #1976d2 !important;
            color: white !important;
            transform: translateY(-50%) scale(1.1) !important; /* MANTÉM CENTRALIZAÇÃO */
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3) !important;
        }
        
        /* Estado correto */
        .single-resolucoes .entry-content .resolucao-letra-botao.correta {
            background-color: #4caf50 !important;
            border-color: #388e3c !important;
            color: white !important;
            transform: translateY(-50%) scale(1.1) !important; /* MANTÉM CENTRALIZAÇÃO */
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3) !important;
        }
        
        /* Estado incorreto */
        .single-resolucoes .entry-content .resolucao-letra-botao.incorreta {
            background-color: #f44336 !important;
            border-color: #d32f2f !important;
            color: white !important;
            transform: translateY(-50%) scale(1.1) !important; /* MANTÉM CENTRALIZAÇÃO */
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3) !important;
        }
        
        /* ========================================
           BOTÃO CONFIRMAR RESPOSTA
        ======================================== */
        
        .single-resolucoes .entry-content .resolucao-botao-confirmar {
            display: inline-block !important;
            background-color: #2196f3 !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 8px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            margin: 1.25rem 0 !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            text-decoration: none !important;
            outline: none !important;
            box-sizing: border-box !important;
        }
        
        .single-resolucoes .entry-content .resolucao-botao-confirmar:hover {
            background-color: #1976d2 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(33, 150, 243, 0.2) !important;
        }
        
        .single-resolucoes .entry-content .resolucao-botao-confirmar:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px rgba(33, 150, 243, 0.2) !important;
        }
        
        /* ========================================
           CONTAINER DE EXPLICAÇÃO
        ======================================== */
        
        .single-resolucoes .entry-content .resolucao-explicacao {
            margin: 1.5rem 0 !important;
            padding: 1.25rem !important;
            border-radius: 12px !important;
            background-color: #fafafa !important;
            border: 1px solid #e0e0e0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
        }
        
        /* Header da explicação */
        .single-resolucoes .entry-content .explicacao-header {
            margin-bottom: 1rem !important;
            padding-bottom: 1rem !important;
            border-bottom: 1px solid #e0e0e0 !important;
        }
        
        .single-resolucoes .entry-content .explicacao-header.verde {
            border-left: 4px solid #4caf50 !important;
            padding-left: 1rem !important;
        }
        
        .single-resolucoes .entry-content .explicacao-header.vermelho {
            border-left: 4px solid #f44336 !important;
            padding-left: 1rem !important;
        }
        
        .single-resolucoes .entry-content .explicacao-header h3 {
            margin: 0 0 0.5rem 0 !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
        }
        
        .single-resolucoes .entry-content .explicacao-header p {
            margin: 0 !important;
            color: #666 !important;
        }
        
        /* Conteúdo da explicação */
        .single-resolucoes .entry-content .explicacao-conteudo {
            margin: 1rem 0 !important;
        }
        
        .single-resolucoes .entry-content .explicacao-conteudo h4 {
            margin: 0 0 0.75rem 0 !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: #333 !important;
        }
        
        .single-resolucoes .entry-content .explicacao-texto {
            background-color: white !important;
            padding: 1rem !important;
            border-radius: 8px !important;
            border: 1px solid #e0e0e0 !important;
            line-height: 1.6 !important;
        }
        
        /* Ações da explicação */
        .single-resolucoes .entry-content .explicacao-acoes {
            margin-top: 1.25rem !important;
            text-align: center !important;
        }
        
        .single-resolucoes .entry-content .resolucao-botao-reset {
            background-color: #757575 !important;
            color: white !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px !important;
            font-size: 0.9rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-transform: none !important;
        }
        
        .single-resolucoes .entry-content .resolucao-botao-reset:hover {
            background-color: #616161 !important;
            transform: translateY(-1px) !important;
        }
        
        /* ========================================
           RESPONSIVIDADE DA INTERAÇÃO
        ======================================== */
        
        /* Tablet */
        @media (max-width: 768px) {
            .single-resolucoes .entry-content .resolucao-letra-botao {
                width: 2.2em !important;
                height: 2.2em !important;
                font-size: 0.9rem !important;
            }
            
            .single-resolucoes .entry-content .resolucao-alternativa {
                padding-left: 2.7rem !important;
                min-height: 2.2em !important;
            }
            
            .single-resolucoes .entry-content .resolucao-botao-confirmar {
                width: 100% !important;
                text-align: center !important;
            }
        }
        
        /* Mobile */
        @media (max-width: 480px) {
            .single-resolucoes .entry-content .resolucao-letra-botao {
                width: 2em !important;
                height: 2em !important;
                font-size: 0.8rem !important;
            }
            
            .single-resolucoes .entry-content .resolucao-alternativa {
                padding-left: 2.5rem !important;
                min-height: 2em !important;
                margin-bottom: 0.75rem !important;
            }
            
            .single-resolucoes .entry-content .resolucao-explicacao {
                padding: 1rem !important;
                margin: 1rem 0 !important;
            }
            
            .single-resolucoes .entry-content .explicacao-texto {
                padding: 0.75rem !important;
            }
        }
        
        /* ========================================
           ESPAÇAMENTO HIERÁRQUICO
        ======================================== */
        
        /* Espaçamento entre elementos principais */
        .single-resolucoes .entry-content > * {
            margin-bottom: 1.25rem !important;
        }
        
        /* Primeiro e último elemento sem margem extra */
        .single-resolucoes .entry-content > *:first-child {
            margin-top: 0 !important;
        }
        
        .single-resolucoes .entry-content > *:last-child {
            margin-bottom: 0 !important;
        }
        
        /* ========================================
           ELEMENTOS RESPONSIVOS
        ======================================== */
        
        /* Imagens responsivas */
        .single-resolucoes .entry-content img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px !important;
            margin: 1.25rem 0 !important;
            display: block !important;
        }
        
        /* Tabelas responsivas */
        .single-resolucoes .entry-content table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 1.25rem 0 !important;
            border: 1px solid #ddd !important;
        }
        
        .single-resolucoes .entry-content table th,
        .single-resolucoes .entry-content table td {
            padding: 0.75rem !important;
            border: 1px solid #ddd !important;
            text-align: left !important;
        }
        
        .single-resolucoes .entry-content table th {
            background-color: #f5f5f5 !important;
            font-weight: bold !important;
        }
        
        /* Códigos */
        .single-resolucoes .entry-content code {
            background-color: #f5f5f5 !important;
            padding: 0.25rem 0.5rem !important;
            border-radius: 4px !important;
            font-family: monospace !important;
        }
        
        .single-resolucoes .entry-content pre {
            background-color: #f5f5f5 !important;
            padding: 1rem !important;
            border-radius: 8px !important;
            overflow-x: auto !important;
            margin: 1.25rem 0 !important;
        }
        
        /* Blockquotes */
        .single-resolucoes .entry-content blockquote {
            border-left: 4px solid #ccc !important;
            padding-left: 1rem !important;
            margin: 1.25rem 0 !important;
            background-color: #f9f9f9 !important;
            padding: 1rem !important;
            border-radius: 0 8px 8px 0 !important;
        }
        
        /* ========================================
           BLOCO REFERÊNCIA
        ======================================== */
        
        .wp-block-suite-resolucoes-referencia {
            font-size: 0.875rem !important;
            color: #666 !important;
            text-align: right !important;
            font-style: italic !important;
            margin: 1.25rem 0 !important;
            padding: 0.5rem !important;
            border-top: 1px solid #eee !important;
        }
        
        /* ========================================
           ESCONDE NAVEGAÇÃO PADRÃO DO TEMA
        ======================================== */
        
        .single-resolucoes .nav-links,
        .single-resolucoes .post-navigation,
        .single-resolucoes .posts-navigation {
            display: none !important;
        }
        
        /* ========================================
           COMPATIBILIDADE COM TEMA ASTRA
        ======================================== */
        
        /* Remove conflitos específicos do Astra */
        .single-resolucoes a {
            text-decoration: underline !important;
        }
        
        .single-resolucoes a:hover {
            text-decoration: none !important;
        }
        
        /* Garante que o conteúdo não seja cortado */
        .single-resolucoes .site-content {
            overflow: visible !important;
        }
        
        /* Remove padding extra do tema */
        .single-resolucoes .ast-article-single {
            padding: 0 !important;
        }
        
        /* ========================================
           FORÇA TOTAL - REMOVE QUALQUER PONTO RESTANTE
        ======================================== */
        
        /* Remove pontos de qualquer pseudo-elemento */
        .single-resolucoes *::before,
        .single-resolucoes *::after {
            content: none !important;
        }
        
        /* Re-adiciona apenas os ::before necessários SEM pontos */
        .single-resolucoes .entry-content ol li:not(.resolucao-alternativa)::before,
        .single-resolucoes .entry-content ul li:not(.resolucao-alternativa)::before {
            content: counter(item, upper-alpha) !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            font-family: monospace !important;
            font-weight: bold !important;
            width: 1.5em !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
        }
        
        /* Para alternativas SEM botão JS (fallback sem ponto) */
        .single-resolucoes .resolucao-alternativa:not(.tem-botao-js)::before {
            content: counter(item, upper-alpha) !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            font-family: monospace !important;
            font-weight: bold !important;
            width: 1.5em !important;
            text-align: center !important;
            color: #666 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
        }
        </style>
        <?php
    }
}

// Inicializa a classe
new ResolucaoEstilos();