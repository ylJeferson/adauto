  /*---------------------------*/
 /* ANIMACAO - CAIXA DE TEXTO */
/*---------------------------*/
document.addEventListener('DOMContentLoaded', function() {
	const Cracha = document.querySelector('.Cracha');
	const CrachaVerso = document.querySelector('.CrachaVerso');
	const CrachaFrente = document.querySelector('.CrachaFrente');
	const CaixaDeBotaoAnimado = document.querySelector('.CaixaDeBotaoAnimado');

	Cracha.addEventListener('click', function() {
		CrachaVerso.classList.toggle('GirarVerso');
		CrachaFrente.classList.toggle('GirarFrente');
	});

	CaixaDeBotaoAnimado.addEventListener('click', function(e) {
		e.stopPropagation();
	});
});
