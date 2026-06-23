<?php
	require_once 'php/config.php';

	function escapar_ldap_valor($valor, $tipo = 'filter') {
		if (function_exists('ldap_escape')) {
			if ($tipo === 'dn' && defined('LDAP_ESCAPE_DN')) {
				return ldap_escape($valor, '', LDAP_ESCAPE_DN);
			}

			if (defined('LDAP_ESCAPE_FILTER')) {
				return ldap_escape($valor, '', LDAP_ESCAPE_FILTER);
			}
		}

		$mapa_filtro = array(
			'\\' => '\\5c',
			'*' => '\\2a',
			'(' => '\\28',
			')' => '\\29',
			chr(0) => '\\00'
		);

		if ($tipo !== 'dn') {
			return strtr($valor, $mapa_filtro);
		}

		return str_replace(
			array('\\', ',', '+', '"', '<', '>', ';', '='),
			array('\\5c', '\\2c', '\\2b', '\\22', '\\3c', '\\3e', '\\3b', '\\3d'),
			$valor
		);
	}

	function valores_ldap($conexao, $entrada, $atributo) {
		if (!$entrada) {
			return array();
		}

		$valores = @ldap_get_values($conexao, $entrada, $atributo);
		if (!is_array($valores) || !isset($valores['count'])) {
			return array();
		}

		$resultado = array();
		for ($i = 0; $i < $valores['count']; $i++) {
			if (isset($valores[$i])) {
				$resultado[] = $valores[$i];
			}
		}

		return $resultado;
	}

	function primeiro_valor($valores, $padrao = null) {
		return isset($valores[0]) ? $valores[0] : $padrao;
	}

	function nome_grupo_ldap($valor, $prefixo) {
		$nome = $valor;
		$partes = explode(',', $valor);

		if (isset($partes[0]) && stripos($partes[0], 'cn=') === 0) {
			$nome = substr($partes[0], 3);
		}

		if ($prefixo !== '' && stripos($nome, $prefixo) !== 0) {
			return null;
		}

		if ($prefixo !== '') {
			$nome = substr($nome, strlen($prefixo));
		}

		$nome = trim($nome);
		if ($nome === '') {
			return null;
		}

		return ucwords(strtolower($nome));
	}

	function adicionar_grupo(&$grupos, $grupo) {
		if ($grupo !== null && !in_array($grupo, $grupos)) {
			$grupos[] = $grupo;
		}
	}

	if (!isset($_SESSION)) session_start();

	$redirecionamento = isset($_POST['redirecionamento']) ? $_POST['redirecionamento'] : '';
	if ($redirecionamento === 'logout') session_unset();

	if ($redirecionamento === 'login') {
		$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
		$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
		$captcha_configurado = $recaptcha === True && !empty($recaptcha_site) && !empty($recaptcha_secreta);

		if (empty($usuario) || empty($senha)) {
			$mensagem_erro = 'Preencha todos os campos!';
		} else if (!function_exists('ldap_connect')) {
			$mensagem_erro = 'A extensao LDAP do PHP nao esta habilitada.';
		} else {
			if (!empty($ldap_ignorar_certificado_tls) && defined('LDAP_OPT_X_TLS_REQUIRE_CERT') && defined('LDAP_OPT_X_TLS_NEVER')) {
				@ldap_set_option(null, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
			}

			$conexao = @ldap_connect($ip_do_servidor_ldap);
			if (!$conexao) {
				$mensagem_erro = 'Nao foi possivel conectar ao servidor LDAP.';
			} else {
				ldap_set_option($conexao, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($conexao, LDAP_OPT_REFERRALS, 0);

				$usuario_de_rede = isset($ldap_dn_do_usuario) && $ldap_dn_do_usuario !== ''
					? sprintf($ldap_dn_do_usuario, escapar_ldap_valor($usuario, 'dn'))
					: $usuario . $dominio;

				$ldap_bind = @ldap_bind($conexao, $usuario_de_rede, $senha);

				if ($ldap_bind) {
					$base_pesquisa_usuarios = isset($base_usuarios_ldap) && $base_usuarios_ldap !== '' ? $base_usuarios_ldap : $basedn;
					$filtro_usuario = isset($ldap_filtro_usuario) && $ldap_filtro_usuario !== ''
						? sprintf($ldap_filtro_usuario, escapar_ldap_valor($usuario))
						: '(samaccountname=' . escapar_ldap_valor($usuario) . ')';
					$atributos = array('thumbnailPhoto', 'displayName', 'cn', 'employeeID', 'employeeNumber', 'uid', 'memberOf');
					$pesquisa_usuario = @ldap_search($conexao, $base_pesquisa_usuarios, $filtro_usuario, $atributos);
					$entrada_usuario = $pesquisa_usuario ? ldap_first_entry($conexao, $pesquisa_usuario) : false;

					if (!$entrada_usuario) {
						$mensagem_erro = 'Usuario autenticado, mas nao encontrado na base LDAP.';
					} else {
						$dn_usuario = ldap_get_dn($conexao, $entrada_usuario);
						$foto = valores_ldap($conexao, $entrada_usuario, 'thumbnailPhoto');
						$nome = valores_ldap($conexao, $entrada_usuario, 'displayName');
						$cn = valores_ldap($conexao, $entrada_usuario, 'cn');
						$employeeID = valores_ldap($conexao, $entrada_usuario, 'employeeID');
						$employeeNumber = valores_ldap($conexao, $entrada_usuario, 'employeeNumber');
						$uid = valores_ldap($conexao, $entrada_usuario, 'uid');
						$memberOf = valores_ldap($conexao, $entrada_usuario, 'memberOf');

						$ADGruposBI = array();
						foreach ($memberOf as $grupo_dn) {
							adicionar_grupo($ADGruposBI, nome_grupo_ldap($grupo_dn, $iniciais_do_grupo_de_permissao_para_o_bi));
						}

						if (empty($ADGruposBI) && !empty($ldap_filtro_grupos_do_usuario)) {
							$base_pesquisa_grupos = isset($base_grupos_ldap) && $base_grupos_ldap !== '' ? $base_grupos_ldap : $basedn;
							$filtro_grupos = sprintf($ldap_filtro_grupos_do_usuario, escapar_ldap_valor($dn_usuario));
							$pesquisa_grupos = @ldap_search($conexao, $base_pesquisa_grupos, $filtro_grupos, array('cn'));
							$entrada_grupo = $pesquisa_grupos ? ldap_first_entry($conexao, $pesquisa_grupos) : false;

							while ($entrada_grupo) {
								$grupo_cn = valores_ldap($conexao, $entrada_grupo, 'cn');
								adicionar_grupo($ADGruposBI, nome_grupo_ldap(primeiro_valor($grupo_cn, ''), $iniciais_do_grupo_de_permissao_para_o_bi));
								$entrada_grupo = ldap_next_entry($conexao, $entrada_grupo);
							}
						}

						$_SESSION['thumbnailPhoto'] = isset($foto[0]) ? 'data:image/jpeg;base64,' . base64_encode($foto[0]) : null;
						$_SESSION['displayName'] = primeiro_valor($nome, primeiro_valor($cn, $usuario));
						$_SESSION['employeeID'] = primeiro_valor($employeeID, primeiro_valor($employeeNumber, primeiro_valor($uid, $usuario)));
						$_SESSION['memberOf'] = $ADGruposBI;
						$_SESSION['DadosBI'] = array();

						foreach ($_SESSION['memberOf'] as $grupo) {
							$wWWHomePage = '';

							if (!empty($iniciais_do_grupo_de_permissao_para_o_bi)) {
								$grupo_ldap = $iniciais_do_grupo_de_permissao_para_o_bi . strtoupper($grupo);
								$filtro_grupo_bi = '(|(samaccountname=' . escapar_ldap_valor($grupo_ldap) . ')(cn=' . escapar_ldap_valor($grupo_ldap) . '))';
								$pesquisa_grupo_bi = @ldap_search($conexao, $basedn, $filtro_grupo_bi, array('wWWHomePage'));
								$valores_grupo_bi = $pesquisa_grupo_bi ? ldap_get_entries($conexao, $pesquisa_grupo_bi) : array();
								$wWWHomePage = isset($valores_grupo_bi[0]['wwwhomepage'][0]) ? $valores_grupo_bi[0]['wwwhomepage'][0] : '';
							}

							$_SESSION['DadosBI'][] = array($grupo, $wWWHomePage);
						}

						$_SESSION['usuario'] = $usuario;
					}
				} else {
					if ($captcha_configurado) {
						$_SESSION['captcha'] = True;
					}

					$mensagem_erro = 'Usuario ou senha incorretos!';
				}

				ldap_close($conexao);
			}
		}
	}

	if (isset($_SESSION['usuario'])) {
		if ($redirecionamento === 'relatorio') {
			require_once 'php/pgPowerBI.php';
			$redirecionamento = '';
			exit;
		}

		require_once 'php/pgListaBI.php';
		$redirecionamento = '';
		exit;
	} else {
		require_once 'php/pgLogin.php';
		$redirecionamento = '';
		exit;
	}
?>
