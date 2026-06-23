<?php
	$mensagem_erro = isset($mensagem_erro) ? $mensagem_erro : '';
	$captcha_configurado = $recaptcha === True && !empty($recaptcha_site) && !empty($recaptcha_secreta);
	if ($captcha_configurado && isset($_SESSION['captcha'])) {
		$captcha = $_SESSION['captcha'];
	} else {
		$captcha = False;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Login</title>

		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="css/.config.css"/>
		<link rel="stylesheet" type="text/css" href="css/cptCaixas.css"/>
		<link rel="stylesheet" type="text/css" href="css/pgLogin.css"/>
		<link rel="shortcut icon" type="image/jpg" href="img/favicon.png"/>

		<?php
			if ($captcha_configurado) {
				echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
			}
		?>
	</head>

	<body>
		<form id="frmLogin" action="" method="post" class="ContainerLogin">
			<div class="CaixaDeImagem">
				<img src="img/brand.png" class="banner-cptCaixas"/>
			</div>

			<div class="CaixaDeRecaptcha">
				<?php
					if ($captcha === True) {
						echo '<div class="g-recaptcha" name="g-recaptcha-response" data-sitekey="' . $recaptcha_site . '"></div>';
					}
				?>
			</div>
			
			<div class="CaixaDeTexto">
				<input type="text" class="input-cptCaixas" placeholder="" autocomplete="off" maxlength="20" name="usuario">
				<span class="span-cptCaixas" data-placeholder="Usuário"></span>
			</div>

			<div class="CaixaDeTexto">
				<input type="password" class="input-cptCaixas" placeholder="" maxlength="30" name="senha">
				<span class="span-cptCaixas" data-placeholder="Senha"></span>
				<p class="erro-pgLogin"><?= $mensagem_erro ?></p>
			</div>

			<div class="CaixaDeBotaoAnimado">
				<button type="submit" class="button-cptCaixas btnLinhasAnimadas" name="redirecionamento" value="login">
					Login
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


		<script src="js/cptCaixas.js"></script>	<!-- PARA FUNCIONAR ANIMAÇÕES  -->
	</body>
</html>
