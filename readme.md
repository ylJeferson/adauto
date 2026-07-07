<p align="center">
  <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub Logo" width="150">
</p>

<h1 align="center">Adauto</h1>

Aplicacao PHP simples para login via LDAP e listagem de grupos/permissoes.

## Diretrizes do projeto

- Seja extremamente conciso e foque apenas na resolucao do problema. Responda diretamente com codigo, comando ou solucao necessaria, evitando rodeios, saudacoes formais e explicacoes longas, salvo quando solicitado.
- O arquivo README deve ser nomeado totalmente em letras minusculas: `readme.md`.
- O `readme.md` deve documentar a estrutura do projeto, seu funcionamento e todas as diretrizes aplicaveis ao projeto.
- Toda nova diretriz definida durante a conversa deve ser registrada no `readme.md` antes de qualquer implementacao relacionada.

## Estrutura

- `index.php`: fluxo principal de sessao, login LDAP e redirecionamento.
- `php/config.php`: configuracoes de LDAP, grupos e reCAPTCHA.
- `php/pgLogin.php`: tela de login.
- `php/pgListaBI.php`: tela de usuario autenticado e listagem de grupos.
- `php/pgPowerBI.php`: exibicao de relatorio em iframe.
- `css/`: estilos da interface.
- `js/`: scripts da interface.
- `img/`: imagens usadas pela aplicacao.

## Funcionamento

1. O usuario informa login e senha.
2. A aplicacao autentica no LDAP configurado.
3. Apos o bind, busca os dados do usuario e seus grupos.
4. Os grupos lidos sao exibidos como permissoes na tela principal.
5. O captcha permanece desativado enquanto `$recaptcha = False`.

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
