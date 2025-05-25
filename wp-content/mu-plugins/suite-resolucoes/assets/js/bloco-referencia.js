(function() {
    var el = wp.element.createElement;
    var RichText = wp.blockEditor.RichText;
    var registerBlockType = wp.blocks.registerBlockType;
    var __ = wp.i18n.__;

    registerBlockType('resolucoes/referencia', {
        title: __('Referência'),
        icon: 'editor-quote',
        category: 'text',
        description: __('Bloco para adicionar referências e fontes aos artigos.'),
        
        attributes: {
            content: {
                type: 'string',
                default: '',
            },
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el(RichText, {
                tagName: 'div',
                className: 'wp-block-resolucoes-referencia',
                value: attributes.content,
                onChange: function(content) {
                    setAttributes({content: content});
                },
                placeholder: __('Digite a referência aqui...'),
                allowedFormats: ['core/italic', 'core/bold'],
            });
        },

        save: function(props) {
            var attributes = props.attributes;
            
            return el(RichText.Content, {
                tagName: 'div',
                className: 'wp-block-resolucoes-referencia',
                value: attributes.content,
            });
        },
    });
})();