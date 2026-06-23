<?php
	$ADFoto = isset($_SESSION['thumbnailPhoto']) ? $_SESSION['thumbnailPhoto'] : 'img/user.png';
	$ADNome = isset($_SESSION['displayName']) ? $_SESSION['displayName'] : 'LIMITE DE 59 CARACTERES';
	$ADCracha = isset($_SESSION['employeeID']) ? $_SESSION['employeeID'] : '123456';
	$DadosBI = isset($_SESSION['DadosBI']) && is_array($_SESSION['DadosBI']) ? $_SESSION['DadosBI'] : array();

	usort($DadosBI, function($a, $b) {
		return strcmp($a[0], $b[0]);
	});
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Listagem Business Intelligence</title>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="css/.config.css"/>
		<link rel="stylesheet" type="text/css" href="css/cptCaixas.css"/>
		<link rel="stylesheet" type="text/css" href="css/pgListaBI.css"/>
		<link rel="shortcut icon" type="image/jpg" href="img/favicon.png"/>

		<script src="js/codBarras.js"></script>
	</head>

	<body>
		<div class="Cracha">
			<form action="" method="post" class="CrachaFrente">
				<div class="CaixaDeImagem">
					<img src="img/brand.png" class="banner-cptCaixas"/>
				</div>

				<div class="CaixaDeImagem">
					<img if="Foto3x4" src="<?= $ADFoto ?>" alt="<?= $ADCracha . ' - ' . $ADNome ?>" class="foto3x4-cptCaixas"/>
				</div>

				<div class="CaixaDeRotulo">
					<input id="Foto3x4" class="hidden-cptCaixas"/>
					<label for="Foto3x4"><?= $ADNome ?></label>
				</div>

				<div class="CaixaDeBotaoAnimado">
					<button type="submit" class="button-cptCaixas btnLinhasAnimadas" name="redirecionamento" value="logout">
						Logout
						<span class="spnLinhasAnimadas">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</span>
					</button>
				</div>
			</form>

			<div class="CrachaVerso">
				<script>
					var barCode = code128('<?= $ADCracha ?>', '273', 'A');
					window.document.write(barCode);
				</script>
			</div>
		</div>

		<div class="ListaBI">
			<?php
				foreach ($DadosBI as $Dados) {
					$Link = !empty($Dados[1]) ? $Dados[1] : '#';
			?>
				<form action="" method="post" class="CaixaDeBotaoComLink">
					<input type="input" class="hidden-cptCaixas" name="relatorio" value="<?= $Link ?>"></button>
					<button type="submit" class="button-cptCaixas" name="redirecionamento" value="relatorio"><?= $Dados[0] ?></button>
				</form>
			<?php
				}
			?>
		</div>


		<script src="js/cptCaixas.js"></script>	<!-- PARA FUNCIONAR ANIMAÇÕES  -->
		<script src="js/pgListaBI.js"></script>
	</body>
</html>
