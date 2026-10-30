<p align="center">
  <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub Logo" width="150">
</p>

<h1 align="center">Adauto</h1>

---

Aplicacao PHP de demonstracao para login via LDAP publico e leitura dos grupos do usuario, com CAPTCHA desativado.

<p align="center">
  <a href="https://github.com/ylJeferson/adauto/archive/refs/heads/main.zip">Download</a> |
  <a href="https://github.com/ylJeferson/adauto/issues/new?title=Bug%3A%20">Reportar Bug</a> |
  <a href="https://github.com/ylJeferson/adauto/issues/new?title=Ideia%3A%20">Sugerir Ideias</a>
</p>

Para apoiar o projeto com uma doacao, [entre em contato pelo GitHub](https://github.com/yljeferson/yljeferson/issues).

## Funcionalidades

- Autenticacao LDAP, sessao do usuario e logout.
- Consulta dos dados do usuario e leitura de grupos por `memberOf`, com busca alternativa por `member`.
- Listagem dos grupos retornados, sem exigir uma DL especifica.
- Pagina de relatorios em iframe quando houver um link configurado; a demonstracao nao fornece relatorios Power BI.

O escopo cobre login e leitura de grupos. A listagem nao comprova autorizacao de acesso a relatorios nem prontidao para producao.

## Estrutura

- `index.php`: fluxo principal de sessao, login LDAP e redirecionamento.
- `php/config.php`: configuracoes de LDAP, grupos e reCAPTCHA.
- `php/pgLogin.php`: tela de login.
- `php/pgListaBI.php`: tela de usuario autenticado e listagem de grupos.
- `php/pgPowerBI.php`: exibicao de relatorio em iframe.
- `css/`: estilos da interface.
- `js/`: scripts da interface.
- `img/`: imagens usadas pela aplicacao.
- `guideline.md`: diretrizes permanentes especificas do projeto.

## Instalacao e execucao

Requisitos: PHP com a extensao LDAP habilitada e suporte a sessoes, navegador e acesso ao servidor LDAP pela porta TCP 636.

```bash
git clone https://github.com/ylJeferson/adauto.git
cd adauto
php -m
php -S 127.0.0.1:8000
```

Confirme que `ldap` aparece em `php -m` antes de iniciar o servidor. Alternativamente, extraia o Download e execute os comandos PHP na raiz extraida. Acesse [a demonstracao local](http://127.0.0.1:8000). O servidor embutido destina-se ao uso local.

### Configuracao de demonstracao

As configuracoes ficam em `php/config.php`:

- Servidor: `ldaps://ipa.demo1.freeipa.org:636`
- Base DN: `dc=demo1,dc=freeipa,dc=org`
- Prefixo de grupos vazio, sem filtro por DL.
- CAPTCHA: `$recaptcha = False`, com `$recaptcha_site` e `$recaptcha_secreta` em branco.

O ambiente de teste usa `$ldap_ignorar_certificado_tls = True` por causa da CA autoassinada do servidor de demonstracao. Essa excecao nao deve ser usada em producao. Com o CAPTCHA desativado, o script do Google nao e carregado e o login nao exige uma resposta de CAPTCHA.

### Uso e validacao manual

1. Entre com uma conta publica de demonstracao. O teste anterior registrou usuario `manager` e senha `Secret123`.
2. Confirme que o login abre a tela do usuario e apresenta os grupos retornados pelo LDAP, sem exigir uma DL especifica.
3. Use Logout e confirme o retorno a tela de login.
4. Informe uma senha incorreta e confirme que a aplicacao permanece na tela de login, exibindo uma mensagem de erro.

O servidor publico, suas contas e seus grupos podem mudar. As credenciais e os grupos do teste anterior (`ipausers`, `employees`, `managers`) sao referencias historicas, nao uma validacao atual. Nao use credenciais corporativas nessa demonstracao.

## Tecnologias

- PHP, extensao LDAP e sessoes.
- FreeIPA via LDAPS.
- HTML, CSS e JavaScript.

## Licenca

[MIT](LICENSE).

## Autor

[yljeferson](https://github.com/yljeferson).
