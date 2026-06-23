const corKazzoLogotipo = getComputedStyle(document.documentElement).getPropertyValue('--kazzo-cor-logotipo');
const transicaoVelocidade = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--transicao-velocidade')) * 1000;

document.addEventListener('DOMContentLoaded', function() {
	  /*----------------------------*/
	 /* ANIMACAO - CAIXA COM TEXTO */
	/*----------------------------*/
	var inputsCaixaDeTexto = document.querySelectorAll('.input-cptCaixas');
	inputsCaixaDeTexto.forEach(function(inputElement) {
		var CaixaDeTextoPai = inputElement.closest('.CaixaDeTexto');

		inputElement.addEventListener('focus', function() {
			CaixaDeTextoPai.classList.add('ComTexto');
		});

		inputElement.addEventListener('input', function() {
			if (this.value.trim() !== '') {
				CaixaDeTextoPai.classList.add('ComTexto');
			} else {
				CaixaDeTextoPai.classList.remove('ComTexto');
			}
		});

		inputElement.addEventListener('blur', function() {
			if (this.value.trim() === '') {
				CaixaDeTextoPai.classList.remove('ComTexto');
			}
		});
	});

	  /*----------------------------------*/
	 /* ANIMACAO LINHAS - CLICK NO BOTAO */
	/*----------------------------------*/
	var buttonsCaixaDeBotao = document.querySelectorAll('.button-cptCaixas');
	buttonsCaixaDeBotao.forEach(function(inputElement) {
		inputElement.addEventListener('click', function() {
			this.classList.add('Clicado');
			setTimeout(() => {
				this.classList.remove('Clicado');
			}, transicaoVelocidade);
		});
	});

	  /*------------------------------------------------------------------------------------------------*/
	 /* COR DA BORDA - ANIMACAO CENTRO PARA BORDA E LUA CRESCENTE - MOUSE OUT/OVER NO BOTAO COM IMAGEM */
	/*------------------------------------------------------------------------------------------------*/
	var btnsCaixaDeBotaoComImagemEAnimacao = document.querySelectorAll('.btnComImagemEAnimacao');
	btnsCaixaDeBotaoComImagemEAnimacao.forEach(function(btnElement) {
		if (btnElement.classList.contains('CentroParaABorda') || btnElement.classList.contains('LuaCrescente')) {
			btnElement.style.backgroundColor = corKazzoLogotipo;
		}
	});
});
