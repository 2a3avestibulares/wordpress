/**
 * Sistema de Interação para Resoluções
 * VERSÃO CORRIGIDA - 24/05/2025
 * Funcionalidades: Seleção, correção, persistência e explicações
 * CORREÇÃO: Removido ponto das letras + alinhamento vertical perfeito
 */

class ResolucaoInteracao {
    constructor() {
        console.log('🚀 ResolucaoInteracao: Inicializando...');
        
        this.config = window.resolucaoConfig || null;
        this.storageKey = 'resolucao_' + (this.config ? this.config.postId : 0);
        this.alternativas = [];
        this.alternativaSelecionada = null;
        this.botaoConfirmar = null;
        this.explicacaoContainer = null;
        this.jaRespondeu = false;
        
        console.log('📋 Config:', this.config);
        
        this.init();
    }
    
    /**
     * Inicialização principal
     */
    init() {
        if (!this.config || !this.config.respostaCorreta) {
            console.log('❌ Sem configuração válida, sistema não ativado');
            return;
        }
        
        console.log('✅ Configuração válida encontrada');
        
        // Aguarda o DOM estar pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }
    
    /**
     * Configuração inicial dos elementos
     */
    setup() {
        console.log('🔧 Iniciando setup...');
        
        this.encontrarAlternativas();
        this.criarBotaoConfirmar();
        this.criarContainerExplicacao();
        this.adicionarEventListeners();
        this.restaurarEstado();
        
        console.log('✅ Sistema de interação ativado:', this.alternativas.length, 'alternativas encontradas');
    }
    
    /**
     * Encontra todas as alternativas e cria áreas clicáveis nas letras
     */
    encontrarAlternativas() {
        console.log('🔍 Procurando alternativas...');
        
        // Procura por listas dentro do conteúdo
        const listas = document.querySelectorAll('.single-resolucoes .entry-content ol, .single-resolucoes .entry-content ul');
        console.log('📝 Listas encontradas:', listas.length);
        
        listas.forEach((lista, indexLista) => {
            const itens = lista.querySelectorAll('li');
            console.log(`Lista ${indexLista + 1}: ${itens.length} itens`);
            
            // Considera como alternativas se tiver entre 2-5 itens (A-E)
            if (itens.length >= 2 && itens.length <= 5) {
                console.log('✅ Lista identificada como alternativas');
                
                itens.forEach((item, index) => {
                    const letra = String.fromCharCode(65 + index); // A, B, C, D, E
                    
                    // Adiciona atributos de identificação
                    item.setAttribute('data-alternativa', letra);
                    item.classList.add('resolucao-alternativa');
                    
                    // Cria elemento clicável para a letra
                    const botaoLetra = document.createElement('span');
                    botaoLetra.className = 'resolucao-letra-botao';
                    botaoLetra.setAttribute('data-letra', letra);
                    botaoLetra.textContent = letra; // SEM PONTO
                    console.log('🔤 Botão criado:', letra, 'textContent:', botaoLetra.textContent);
                    // Aplica estilos inline como fallback
                    this.aplicarEstilosInline(botaoLetra);
                    
                    // Substitui o ::before por elemento real
                    item.classList.add('tem-botao-js'); // Classe para esconder ::before
                    item.insertBefore(botaoLetra, item.firstChild);
                    
                    console.log(`🔤 Criado botão para alternativa ${letra}`);
                    
                    this.alternativas.push({
                        elemento: item,
                        botaoLetra: botaoLetra,
                        letra: letra,
                        texto: item.textContent.trim()
                    });
                });
                
                // Adiciona classe à lista pai
                lista.classList.add('resolucao-lista-alternativas');
            }
        });
        
        console.log('📊 Total de alternativas criadas:', this.alternativas.length);
    }
    
    /***
     * Aplica estilos inline como fallback para garantir funcionamento
     * ✅ CORRIGIDO: Centralização vertical perfeita
     */
    aplicarEstilosInline(botaoLetra) {
        const estilosBase = {
            'position': 'absolute',
            'left': '0',
            'top': '50%', // ✅ CENTRALIZAÇÃO VERTICAL
            'transform': 'translateY(-50%)', // ✅ CENTRALIZAÇÃO VERTICAL
            'width': '2.5em',
            'height': '2.5em',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center',
            'font-family': 'monospace',
            'font-weight': 'bold',
            'font-size': '1rem',
            'cursor': 'pointer',
            'border-radius': '50%',
            'transition': 'all 0.3s ease',
            'background-color': '#f5f5f5',
            'border': '2px solid #e0e0e0',
            'color': '#333',
            'user-select': 'none',
            'z-index': '10',
            'box-sizing': 'border-box',
            'text-decoration': 'none'
        };
        
        Object.assign(botaoLetra.style, estilosBase);
    }
    
    /**
     * Aplica estilos de estado via JavaScript
     * ✅ CORRIGIDO: Mantém centralização em todos os estados
     */
    aplicarEstadoVisual(botaoLetra, estado) {
        // Remove estados anteriores
        botaoLetra.classList.remove('selecionada', 'correta', 'incorreta');
        
        // Aplica novo estado
        if (estado) {
            botaoLetra.classList.add(estado);
        }
        
        // Fallback com estilos inline
        switch (estado) {
            case 'selecionada':
                Object.assign(botaoLetra.style, {
                    'background-color': '#2196f3',
                    'border-color': '#1976d2',
                    'color': 'white',
                    'transform': 'translateY(-50%) scale(1.1)', // ✅ MANTÉM CENTRALIZAÇÃO
                    'box-shadow': '0 2px 8px rgba(33, 150, 243, 0.3)'
                });
                break;
            case 'correta':
                Object.assign(botaoLetra.style, {
                    'background-color': '#4caf50',
                    'border-color': '#388e3c',
                    'color': 'white',
                    'transform': 'translateY(-50%) scale(1.1)', // ✅ MANTÉM CENTRALIZAÇÃO
                    'box-shadow': '0 2px 8px rgba(76, 175, 80, 0.3)'
                });
                break;
            case 'incorreta':
                Object.assign(botaoLetra.style, {
                    'background-color': '#f44336',
                    'border-color': '#d32f2f',
                    'color': 'white',
                    'transform': 'translateY(-50%) scale(1.1)', // ✅ MANTÉM CENTRALIZAÇÃO
                    'box-shadow': '0 2px 8px rgba(244, 67, 54, 0.3)'
                });
                break;
            default:
                // Reset para estado padrão
                Object.assign(botaoLetra.style, {
                    'background-color': '#f5f5f5',
                    'border-color': '#e0e0e0',
                    'color': '#333',
                    'transform': 'translateY(-50%) scale(1)', // ✅ MANTÉM CENTRALIZAÇÃO
                    'box-shadow': 'none'
                });
        }
    }
    
    /**
     * Cria o botão "Confirmar resposta"
     */
    criarBotaoConfirmar() {
        this.botaoConfirmar = document.createElement('button');
        this.botaoConfirmar.className = 'resolucao-botao-confirmar';
        this.botaoConfirmar.textContent = 'Confirmar resposta';
        this.botaoConfirmar.style.display = 'none';
        
        // Estilos inline como fallback
        Object.assign(this.botaoConfirmar.style, {
            'background-color': '#2196f3',
            'color': 'white',
            'border': 'none',
            'padding': '0.75rem 1.5rem',
            'border-radius': '8px',
            'font-size': '1rem',
            'cursor': 'pointer',
            'margin': '1.25rem 0'
        });
        
        // Insere após a última lista de alternativas
        const ultimaLista = document.querySelector('.resolucao-lista-alternativas:last-of-type');
        if (ultimaLista) {
            ultimaLista.insertAdjacentElement('afterend', this.botaoConfirmar);
            console.log('✅ Botão confirmar criado');
        }
    }
    
    /**
     * Cria container para a explicação
     */
    criarContainerExplicacao() {
        this.explicacaoContainer = document.createElement('div');
        this.explicacaoContainer.className = 'resolucao-explicacao';
        this.explicacaoContainer.style.display = 'none';
        
        // Estilos inline como fallback
        Object.assign(this.explicacaoContainer.style, {
            'margin': '1.5rem 0',
            'padding': '1.25rem',
            'border-radius': '12px',
            'background-color': '#fafafa',
            'border': '1px solid #e0e0e0'
        });
        
        // Insere após o botão confirmar
        if (this.botaoConfirmar) {
            this.botaoConfirmar.insertAdjacentElement('afterend', this.explicacaoContainer);
            console.log('✅ Container de explicação criado');
        }
    }
    
    /**
     * Adiciona event listeners
     * ✅ CORRIGIDO: Hover mantém centralização
     */
    adicionarEventListeners() {
        // Click nas letras (botões)
        this.alternativas.forEach(alt => {
            alt.botaoLetra.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('🖱️ Clique na alternativa:', alt.letra);
                this.selecionarAlternativa(alt.letra);
            });
            
            // Hover effect via JavaScript - ✅ MANTÉM CENTRALIZAÇÃO
            alt.botaoLetra.addEventListener('mouseenter', () => {
                if (!this.jaRespondeu && !alt.botaoLetra.classList.contains('selecionada')) {
                    Object.assign(alt.botaoLetra.style, {
                        'background-color': '#e3f2fd',
                        'border-color': '#90caf9',
                        'transform': 'translateY(-50%) scale(1.05)' // ✅ MANTÉM CENTRALIZAÇÃO
                    });
                }
            });
            
            alt.botaoLetra.addEventListener('mouseleave', () => {
                if (!this.jaRespondeu && !alt.botaoLetra.classList.contains('selecionada')) {
                    Object.assign(alt.botaoLetra.style, {
                        'background-color': '#f5f5f5',
                        'border-color': '#e0e0e0',
                        'transform': 'translateY(-50%) scale(1)' // ✅ MANTÉM CENTRALIZAÇÃO
                    });
                }
            });
        });
        
        // Click no botão confirmar
        this.botaoConfirmar.addEventListener('click', () => {
            console.log('🖱️ Clique em confirmar resposta');
            this.confirmarResposta();
        });
        
        console.log('✅ Event listeners adicionados');
    }
    
    /**
     * Seleciona uma alternativa
     */
    selecionarAlternativa(letra) {
        if (this.jaRespondeu) return;
        
        console.log('🎯 Selecionando alternativa:', letra);
        
        // Remove seleção anterior
        this.alternativas.forEach(alt => {
            this.aplicarEstadoVisual(alt.botaoLetra, null);
        });
        
        // Adiciona nova seleção
        const alternativa = this.alternativas.find(alt => alt.letra === letra);
        if (alternativa) {
            this.aplicarEstadoVisual(alternativa.botaoLetra, 'selecionada');
            this.alternativaSelecionada = letra;
            
            // Mostra botão confirmar
            this.botaoConfirmar.style.display = 'block';
            
            console.log('✅ Alternativa selecionada:', letra);
        }
    }
    
    /**
     * Confirma a resposta e mostra resultado
     */
    confirmarResposta() {
        if (!this.alternativaSelecionada || this.jaRespondeu) return;
        
        console.log('✅ Confirmando resposta...');
        
        this.jaRespondeu = true;
        const acertou = this.alternativaSelecionada === this.config.respostaCorreta;
        
        console.log('📊 Resultado:', {
            selecionada: this.alternativaSelecionada,
            correta: this.config.respostaCorreta,
            acertou: acertou
        });
        
        // Adiciona classes de resultado
        this.alternativas.forEach(alt => {
            if (alt.letra === this.config.respostaCorreta) {
                this.aplicarEstadoVisual(alt.botaoLetra, 'correta');
            } else if (alt.letra === this.alternativaSelecionada && !acertou) {
                this.aplicarEstadoVisual(alt.botaoLetra, 'incorreta');
            } else {
                this.aplicarEstadoVisual(alt.botaoLetra, null);
            }
            
            // Remove cursor pointer
            alt.botaoLetra.style.cursor = 'default';
        });
        
        // Esconde botão confirmar
        this.botaoConfirmar.style.display = 'none';
        
        // Mostra explicação
        this.mostrarExplicacao(acertou);
        
        // Salva no localStorage
        this.salvarEstado();
    }
    
    /**
     * Mostra a explicação da resposta
     */
    mostrarExplicacao(acertou) {
        console.log('📖 Mostrando explicação, acertou:', acertou);
        
        const titulo = acertou ? '✅ Resposta correta!' : '❌ Resposta incorreta';
        const corAlternativa = acertou ? 'verde' : 'vermelho';
        
        let html = `
            <div class="explicacao-header ${corAlternativa}">
                <h3>${titulo}</h3>
                <p>A resposta correta é a alternativa <strong>${this.config.respostaCorreta}</strong>.</p>
            </div>
        `;
        
        if (this.config.explicacao && this.config.explicacao.trim()) {
            html += `
                <div class="explicacao-conteudo">
                    <h4>Explicação:</h4>
                    <div class="explicacao-texto">
                        ${this.config.explicacao}
                    </div>
                </div>
            `;
        }
        
        // Botão para tentar novamente
        html += `
            <div class="explicacao-acoes">
                <button class="resolucao-botao-reset" onclick="resolucaoInteracao.resetar()">
                    Tentar novamente
                </button>
            </div>
        `;
        
        this.explicacaoContainer.innerHTML = html;
        this.explicacaoContainer.style.display = 'block';
        
        // Scroll suave até a explicação
        setTimeout(() => {
            this.explicacaoContainer.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 100);
    }
    
    /**
     * Reseta o estado para tentar novamente
     */
    resetar() {
        console.log('🔄 Resetando estado...');
        
        this.jaRespondeu = false;
        this.alternativaSelecionada = null;
        
        // Remove todas as classes de estado
        this.alternativas.forEach(alt => {
            this.aplicarEstadoVisual(alt.botaoLetra, null);
            alt.botaoLetra.style.cursor = 'pointer';
        });
        
        // Esconde elementos
        this.botaoConfirmar.style.display = 'none';
        this.explicacaoContainer.style.display = 'none';
        
        // Remove do localStorage
        localStorage.removeItem(this.storageKey);
        
        console.log('✅ Estado resetado');
    }
    
    /**
     * Salva estado no localStorage
     */
    salvarEstado() {
        const estado = {
            alternativaSelecionada: this.alternativaSelecionada,
            respostaCorreta: this.config.respostaCorreta,
            jaRespondeu: this.jaRespondeu,
            timestamp: Date.now()
        };
        
        localStorage.setItem(this.storageKey, JSON.stringify(estado));
        console.log('💾 Estado salvo no localStorage');
    }
    
    /**
     * Restaura estado do localStorage
     */
    restaurarEstado() {
        try {
            const estadoSalvo = localStorage.getItem(this.storageKey);
            if (!estadoSalvo) {
                console.log('📦 Nenhum estado salvo encontrado');
                return;
            }
            
            const estado = JSON.parse(estadoSalvo);
            console.log('📦 Estado encontrado:', estado);
            
            // Verifica se é do mesmo post e resposta correta
            if (estado.respostaCorreta !== this.config.respostaCorreta) {
                console.log('⚠️ Estado obsoleto, removendo...');
                localStorage.removeItem(this.storageKey);
                return;
            }
            
            // Restaura se respondeu
            if (estado.jaRespondeu && estado.alternativaSelecionada) {
                console.log('🔄 Restaurando estado salvo...');
                
                this.alternativaSelecionada = estado.alternativaSelecionada;
                this.jaRespondeu = true;
                
                // Aplica estados visuais
                this.alternativas.forEach(alt => {
                    if (alt.letra === this.config.respostaCorreta) {
                        this.aplicarEstadoVisual(alt.botaoLetra, 'correta');
                    } else if (alt.letra === estado.alternativaSelecionada && 
                             estado.alternativaSelecionada !== this.config.respostaCorreta) {
                        this.aplicarEstadoVisual(alt.botaoLetra, 'incorreta');
                    }
                    alt.botaoLetra.style.cursor = 'default';
                });
                
                // Mostra explicação
                const acertou = estado.alternativaSelecionada === this.config.respostaCorreta;
                this.mostrarExplicacao(acertou);
                
                console.log('✅ Estado restaurado com sucesso');
            }
        } catch (error) {
            console.error('❌ Erro ao restaurar estado:', error);
            localStorage.removeItem(this.storageKey);
        }
    }
}

// Inicialização global
let resolucaoInteracao;

// Debug para verificar se o script está carregando
console.log('🎬 Script de interação carregado');

// Inicia quando o script carrega
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('📄 DOM pronto, iniciando sistema...');
        resolucaoInteracao = new ResolucaoInteracao();
    });
} else {
    console.log('📄 DOM já pronto, iniciando sistema...');
    resolucaoInteracao = new ResolucaoInteracao();
}

console.log('🔥 VERSÃO NOVA DO JS CARREGADA - SEM PONTOS');