# adauto

Aplicacao PHP simples para login via LDAP e listagem de grupos/permissoes.

## LDAP publico de teste

O projeto esta configurado para o servidor publico de demonstracao do FreeIPA:

- Servidor: `ldaps://ipa.demo1.freeipa.org:636`
- Base DN: `dc=demo1,dc=freeipa,dc=org`
- Usuario testado: `manager`
- Senha: `Secret123`
- Grupos lidos no teste: `ipausers`, `employees`, `managers`

O servidor usa uma CA autoassinada, por isso a configuracao de teste deixa
`$ldap_ignorar_certificado_tls = True`. Para producao, use uma CA confiavel e
desative essa excecao.

## Captcha

O reCAPTCHA esta desativado e as chaves foram deixadas em branco em
`php/config.php`. Com essa configuracao, a tela nao carrega o script do Google e
o login nao exige resposta de captcha.
