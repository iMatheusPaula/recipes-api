# Recipes API

Teste técnico de uma API de receitas gastronômicas utilizando Laravel

## O Teste

Criar uma API para um site de receitas gastronômicas.

* A API deve conter um sistema de autenticação completo (login, logoff e register).
* Após logado o usuário deve poder cadastrar novas receitas além de visualizar, editar ou excluir receitas já
  existentes.
* Visitantes devem poder dar uma nota de 1 a 5 para uma receita, além de criar um comentário sobre a mesma.
* Ao exibir uma receita, o sistema deve mostrar seus respectivos comentários, avaliações e uma nota média de todas as
  avaliações que foram dadas para aquela receita.

O projeto deverá ser hospedado em um repositório público no github bem como sua documentação.(instalação, rotas, etc…),
após o término do teste, o candidato deve enviar o link do repositório.

Neste teste será avaliado o método que o desenvolvedor solucionou o problema, bem como o padrão de projeto utilizado.

## Instalação do projeto

### Requisitos

* PHP >=8.1
* Composer
* Docker (opcional, se quiser usar o mysql já configurado, caso contrário, pode usar qualquer outro banco de dados
  suportado pelo Laravel)

### Passos

Após clonar o repositório:

1. Configure um arquivo `.env` a partir do arquivo `.env.example`
2. Instale as dependências do PHP `composer install`
3. Gere a chave da aplicação `php artisan key:generate`
4. Execute as migrações: `php artisan migrate`
5. Inicie o servidor de desenvolvimento `php artisan serve`

## Auth

Para autenticação, foi utilizado o Laravel Sanctum, com bearer token.

Endpoints:

```
POST /api/register
POST /api/login
POST /api/logout
```

Headers para endpoints protegidos:

```
Authorization: Bearer {token}
```

## Receitas

Qualquer usuário poderá visualizar todas as receitas, mas apenas usuários autenticados poderão criar, editar ou excluir
receitas. Cada receita é associada a um usuário, e somente o proprietário da receita pode editá-la ou excluí-la.

A regra de update e delete foram definidas no middleware `can`, na própria rota, ela é realizada através da Policy de
Recipe

### Avaliações (Reviews)

O sistema permite que visitantes (sem necessidade de autenticação) avaliem as receitas com uma nota de 1 á 5 e deixem um
comentário. Cada receita exibe seus comentários, avaliações e uma nota média calculada a partir de todas as avaliações
recebidas.

Para evitar várias avaliações do mesmo visitante, seja ele autenticado ou não, existe uma validação no `ReviewRequest`
que verifica se o usuário já avaliou a receita, através do seu IP que fica armazenado no banco de dados junto a sua
avaliação. Caso o usuário já tenha avaliado a receita, uma exceção é lançada e o usuário é notificado de que já avaliou
a receita.

### Endpoints

```
GET /api/recipes - Listar todas (público)
GET /api/recipes/{recipe} - Visualizar uma receita específica (público)
POST /api/recipes - Criar uma nova receita (autenticado + id do usuário automático)
PUT /api/recipes/{recipe} - Atualizar (autenticado + proprietário)
DELETE /api/recipes/{recipe} - Excluir (autenticado + proprietário)
POST /api/recipes/{recipe}/reviews - Criar uma nova avaliação para uma receita específica (público)
```

Ao visualizar uma ou todas as receitas, as avaliações associadas e o usuário criador são incluídas na response da api. A
média das avaliações é calculada automaticamente no modelo `Recipe` através de um mutator `averageRating`, que retorna
junto a receita o campo `average_rating`.

## Documentações utilizadas

* [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)
* [Laravel Policies](https://laravel.com/docs/12.x/authorization#creating-policies)
* [Laravel Validation](https://laravel.com/docs/12.x/validation#performing-additional-validation-on-form-requests)
* [Laravel Eloquent Mutators](https://laravel.com/docs/12.x/eloquent-mutators)
* [Pest Test Coverage](https://pestphp.com/docs/test-coverage)
