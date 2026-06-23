<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Login</title>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

		<link rel="stylesheet" type="text/css" href="css/.config.css"/>
		<link rel="stylesheet" type="text/css" href="css/cptCaixas.css"/>
		<link rel="stylesheet" type="text/css" href="css/pgPowerBI.css"/>
		<link rel="shortcut icon" type="image/jpg" href="img/favicon.png"/>
	</head>

	<body>
		<div class="CaixaDeBotaoComImagemEAnimacao">
			<i class="fa fa-arrow-left fa-2x btnComImagemEAnimacao LuaCrescente" aria-hidden="true">
			<a href="<?= $_SERVER['REQUEST_URI'] ?>">
				<i class="fa fa-arrow-left fa-2x btnComImagemEAnimacao LuaCrescente" aria-hidden="true"></i></i>
			</a>
		</div>

		<iframe class="PowerBI" src="<?= $_POST['relatorio'] ?>"></iframe>

		<!-- <script src="https://microsoft.github.io/PowerBI-JavaScript/dist/powerbi.min.js"></script> -->
		<script src="js/cptCaixas.js"></script>	<!-- PARA FUNCIONAR ANIMAÇÕES  -->
		<script src="js/pgPowerBI.js"></script>
	</body>
</html>
