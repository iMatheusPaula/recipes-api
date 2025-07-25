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

## Documentações utilizadas

* [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)
