# Documentação
Foi usado a última versão do Laravel disponível no momento da criação do projeto.

## Models / Entidades
Foi usado para essa aplicação os Models/Entidades (todos eles têm **id**(number), **created_at**(date) e **updated_at**(date)):

### User (table: users) - Usuários
* **fullname**(string): nome completo do usuário
* **email**(string): email do usuário, também usado para login
* **cellphone**(string): telefone do usuário (Max: 14 carácteres)
* **role**(string): função do usuário (admin, manager, common)
* **password**(string): senha do usuário para acesso a plataforma (senha é criptografada antes de ser armazenada)

### Category (table: categories) - Categorias
* **name**(string): nome da categoria

### Supplier (table: suppliers) - Fornecedores
* **name**(string): nome do fornecedor
* **cnpj**(string): cnpj do fornecedor (armazenado sem as pontuaçãoes, com máximo 14 dígitos)
* **contact**(string): contato dos fornecedor (não foi especificado o que seria, então trarei como campo livre de texto)

### Product (table: products) - Produtos
* **name**(string): nome do produto
* **code**(string): código do produto (máximo 10 carácteres)
* **description**(text): descrição do produto
* **cost_price**(decimal): preço de custo do produto (até 10 dígitos inteiros e 2 decimais)
* **selling_price**(decimal): preço de venda do produto (até 10 dígitos inteiros e 2 decimais)
* **minimum_stock**(unsigned smallInteger): estoque mínimo de um produto
* **expiration_date**(date): data de expiração do produto
* **foreignIDs**: Category e Supplier

### Stock (table: stocks) - Estoques
* **quantity**(unsigned smallInteger): quantidade disponível no estoque
* **foreignIDs**: Product

### StockLog (table: stock_logs) - Registros de Estoque
* **quantity**(unsigned smallInteger): quantidade que foi modificada
* **type**(string): o tipo de transação do estoque (purchase, return, sale, loss, adjustment, creation)
* **foreignIDs**: Stock e User

## Database
Para configurar o seu banco de dados, faça uma copia do arquivo ".env.example" e modifique, caso necessário, os valores para fazer a conexão com o servidor MYSQL e o Database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=raizato
DB_USERNAME=root
DB_PASSWORD=
```

## Estrutura
Foi usado a estrutura padrão recomendada pela documentação do Laravel, junto com os comandos para criar os arquivos com os mesmos padrões, por exemplo:
```
php artisan make:Controller UserController
```

### Autenticação
Essa parte da aplicação foi feita com a ajuda do Laravel Sanctum que já cria alguns controles e o Model incial do usuário, além de já garantir uma segurança maior usando CSRF. Ainda foi implementado as rotas para login e logout para garantir as sessões dos usuários e comunicação com o frontend.

## Iniciando Aplicação
Depois de ter toda a base Laravel instalada, entre na pasta do projeto para iniciar e rodar alguns comandos:
```
cd app-folder
npm install && npm run build
composer run dev
```
Após realizar esses comandos, a aplicação Laravel já estará executando em modo de desenvolvimento. Para outros tipos de execução, consultar documentação Laravel abaixo.

Agora, vamos precisar iniciar o banco de dados da aplicação, rodando as ***migrations*** (para visualizá-las acesse *database/migrations*). Estando na pasta raiz do projeto, execute:
```
php artisan migrate

// foram criadas algumas seeds para facilitar o preenchimento do banco, para isso rode
php artisan migrate --seed

// caso precisar começar as migrations novamnete execute (podendo ser com --seed no final ou não)
php artisan migrate:refresh
```
Após isso, a aplicação backend deve estar exposta, por padrão, em localhost:8000. Caso houver alteração da posta ou url, fique atento pois você terá que ajustar no frontend também.

# Considerações
Infelizmente o tempo limite estava chegando e não houve espaço para produção de tetes unitários.

Porém, se for do interesse você pode ver alguns outros projetos que contém testes:
* [Frontend React](https://github.com/BrunoLambert/elo7-challange/tree/main/app/components/Jobs/__tests__)
* 

# Laravel Default README

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
