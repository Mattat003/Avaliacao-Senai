//VALIDAÇÃO DE CAMPO

document.addEventListener('DOMContentLoaded', function() {
    // Configura validações para formulários de produto
    const formsProduto = document.querySelectorAll('form');
    formsProduto.forEach(form => {
        if (form.querySelector('input[name="qtde"], input[name="valor_unit"]')) {
            form.addEventListener('submit', validarFormularioProduto);
            
            // Configura validação em tempo real para campos numéricos
            const camposNumericos = form.querySelectorAll('input[name="qtde"], input[name="valor_unit"]');
            camposNumericos.forEach(campo => {
                // Eventos para bloquear qualquer entrada não numérica
                campo.addEventListener('input', filtrarNumeros);
                campo.addEventListener('keydown', bloquearTeclasNaoNumericas);
                campo.addEventListener('paste', bloquearColagemNaoNumerica);
                campo.addEventListener('drop', bloquearArrastarNaoNumerico);
                campo.addEventListener('change', validarCampoNumerico);
            });
        }
    });
});

// Função principal que filtra qualquer caractere não numérico
function filtrarNumeros(event) {
    const campo = event.target;
    let valor = campo.value;
    
    // Remove TODOS os caracteres não numéricos (exceto ponto para valor_unit)
    if (campo.name === 'qtde') {
        // Quantidade - apenas números inteiros
        valor = valor.replace(/[^0-9]/g, '');
    } else {
        // Valor unitário - números com ponto decimal
        valor = valor.replace(/[^0-9.]/g, '');
        
        // Garante apenas um ponto decimal
        const partes = valor.split('.');
        if (partes.length > 2) {
            valor = partes[0] + '.' + partes[1];
        }
        
        // Limita a 2 casas decimais
        if (partes.length > 1 && partes[1].length > 2) {
            valor = partes[0] + '.' + partes[1].substring(0, 2);
        }
    }
    
    // Atualiza o valor do campo
    if (campo.value !== valor) {
        campo.value = valor;
    }
}

// Bloqueia teclas não numéricas antes mesmo de serem digitadas
function bloquearTeclasNaoNumericas(event) {
    const tecla = event.key;
    const campo = event.target;
    
    // Teclas permitidas: números, ponto (apenas para valor_unit), teclas de controle
    const teclasPermitidas = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 
        'ArrowUp', 'ArrowDown', 'Home', 'End'
    ];
    
    // Permite ponto apenas para valor_unit
    if (campo.name === 'valor_unit') {
        teclasPermitidas.push('.');
    }
    
    // Bloqueia qualquer tecla não permitida
    if (!teclasPermitidas.includes(tecla)) {
        event.preventDefault();
        return false;
    }
    
    // Bloqueia múltiplos pontos
    if (tecla === '.' && campo.value.includes('.')) {
        event.preventDefault();
        return false;
    }
}

// Bloqueia completamente colagem de conteúdo não numérico
function bloquearColagemNaoNumerica(event) {
    event.preventDefault();
    const clipboardData = event.clipboardData || window.clipboardData;
    const textoColado = clipboardData.getData('text');
    
    // Filtra apenas os caracteres numéricos permitidos
    let textoFiltrado;
    if (event.target.name === 'qtde') {
        textoFiltrado = textoColado.replace(/[^0-9]/g, '');
    } else {
        textoFiltrado = textoColado.replace(/[^0-9.]/g, '');
        // Remove pontos extras
        const partes = textoFiltrado.split('.');
        if (partes.length > 1) {
            textoFiltrado = partes[0] + '.' + partes.slice(1).join('');
        }
    }
    
    // Insere apenas o conteúdo filtrado
    document.execCommand('insertText', false, textoFiltrado);
}

// Bloqueia arrastar e soltar conteúdo não numérico
function bloquearArrastarNaoNumerico(event) {
    event.preventDefault();
}

// Validação final do campo numérico
function validarCampoNumerico(event) {
    const campo = event.target;
    if (campo.name === 'qtde' && !/^[0-9]+$/.test(campo.value)) {
        campo.value = '';
    } else if (campo.name === 'valor_unit' && !/^[0-9]+(\.[0-9]{1,2})?$/.test(campo.value)) {
        campo.value = '';
    }
}

// Validação do formulário (mantida igual para outros campos)
function validarFormularioProduto(event) {
    const form = event.target;
    let valido = true;
    
    // Validação dos campos numéricos
    const qtde = form.querySelector('input[name="qtde"]');
    if (qtde && !/^[0-9]+$/.test(qtde.value)) {
        alert('Quantidade deve conter apenas números inteiros');
        qtde.focus();
        valido = false;
    }
    
    const valorUnit = form.querySelector('input[name="valor_unit"]');
    if (valorUnit && !/^[0-9]+(\.[0-9]{1,2})?$/.test(valorUnit.value)) {
        alert('Valor unitário deve conter apenas números (ex: 10.99)');
        valorUnit.focus();
        valido = false;
    }
    
    if (!valido) {
        event.preventDefault();
    }
}