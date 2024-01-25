# Random String

## Requisitos do módulo

> ... um módulo simples, só para conferirmos o código e forma de desenvolvimento.

Segue neste repositório, 45 arquivos.

>
> Segue a descrição:
>
> * Fazer um módulo em Magento 2 que se comunica via graphQl.
>

No endpoint `graphql`, método `GET`,  `query` `randomstring`.

> * Ao fazer a chamada em graphQl o módulo deve salvar um valor qualquer (string) no banco de dados

Gerando uma string aleatória, `random_string` e salvando no banco de dados na table `random_table`.

> * A visualização dos dados salvos deve ser feita como uma lista, no admin... 

No **Admin**, menu **Super**, opção **Grid**.

> ... e dentro do perfil do usuário.

Em **My Account**, aba **Random Strings**.

> * Criar testes unitários.

Foi criado teste para a principal funcionalidade: criar a string aleatória via GraphQL

> Mandar o passo a passo para instalação e execução.

Segue abaixo.

## Sobre a escrita

Existem poucos comentários no código - devido à tipagem do PHP na maior parte das classes e métodos, os comentários não são necessários. 

Porém, é algo que pode ser acordado com a equipe, onde comentar ou não.

## Instalação

Para instalação  do módulo siga os seguintes pasos:

1. clone

```bash
git clone https://github.com/ribahh-4738/RandomString.git
```

2. copiar pasta `Super` para app/code

```bash
cp -r RandomString/Super [instalation_path]/app/code/
```

3. instalar o módulo

```bash
bin/magento module:enable Super_RandomString;
bin/magento setup:upgrade;
```

Para maiores informações sobre instalações de módulos no Magento 2, consulte [Enable or disable modules](https://devdocs.magento.com/guides/v2.4/install-gde/install/cli/install-cli-subcommands-enable.html) na documentação oficial.

## GraphQL

Efetue login no **Front-end** como **Customer**, em seguida **Sign In** no menu superior ou customer/account/login na URL.

Se no ambiente estiver instalado o sample data , efetue login com as seguintes credenciais:

```bash
# customer
roni_cost@example.com
# pwd
roni_cost3@example.com
```

Se possuir outro usuário **Customer**, efetue login com este, do contrário criar customer - no **Front-end**, em **Create Account** no menu superior ou /customer/account na URL.

Instale o [Altair GraphQL Client](https://chromewebstore.google.com/detail/flnheeellpciglgpaodhkhmapeljopja), faça a seguinte requisição na URL_DO_MAGENTO/graphql/ método GET para gerar a **random string**

```graphql
query {
  randomstring {
    random_string
  }
}
```

Ou, para ver os dados do usuário logado enquanto gera a **random string**

```graphql
query {
  randomstring {
    entity_id
    firstname
    lastname
    email
    random_string
  }
}
```

![](./1-Altair.png)

## My Account

No **Front-end** com o usuário **Customer** logado, em **My Account** selecione **Random Strings** no menu à esquerda.

![](./2-My-Account.png)

## Admin 

No **Admin**, no menu à esquerda, selecione **Super**, em seguida **Grid** para visualizar uma listagem das **Random Strings**.

![](./3-Grid-Magento-Admin.png)

No **Admin**, no menu à esquerda, selecione **Super**, em seguida **Configuration** para visualizar um painel de configuração do módulo.

![](./4-Admin-Configuration.png)

## Teste Unitário

Um teste unitário foi criado para validar o retorno da API GraphQL: `Super/RandomString/Test/GraphQl/RandomTest.php`. Pode ser utilizado o **PhpStorm** para rodar o teste, botão direito em cima do arquivo, **Run 'RandomTest.php (PHPUnit)'**

