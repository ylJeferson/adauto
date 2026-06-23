<?php
	$dominio = '';
	$basedn = 'dc=demo1,dc=freeipa,dc=org';
	$base_usuarios_ldap = 'cn=users,cn=accounts,' . $basedn;
	$base_grupos_ldap = 'cn=groups,cn=accounts,' . $basedn;
	$ip_do_servidor_ldap = 'ldaps://ipa.demo1.freeipa.org:636';
	$ldap_dn_do_usuario = 'uid=%s,' . $base_usuarios_ldap;
	$ldap_filtro_usuario = '(uid=%s)';
	$ldap_filtro_grupos_do_usuario = '(member=%s)';
	$ldap_ignorar_certificado_tls = True; // FreeIPA demo uses a self-signed CA.
	$iniciais_do_grupo_de_permissao_para_o_bi = '';
	$nome_comum_para_o_bi = 'CN=' . $iniciais_do_grupo_de_permissao_para_o_bi;

	$recaptcha = False;
	$recaptcha_site = '';
	$recaptcha_secreta = '';
?>
