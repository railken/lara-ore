# lara-ore

Because it's easier 


## Walkthrough

Creating a new laravel project

```bash
composer create-project --prefer-dist laravel/laravel ore
```

### User
1) Installing User Package

```bash
composer require railken/lara-ore-user
``` 

2) Delete the migration of the user in database/*

3) Creating your app/User.php

```php
<?php
namespace App;

use Railken\LaraOre\User\User as BaseUser;

class User extends BaseUser {

}
```

The problem now is that the package use the BaseUser and not your app\User.php. To solve this publish the config

```bash
php artisan vendor:publish --provider="Railken\LaraOre\UserServiceProvider" --tag=config
```

Now you can edit 'entity' and set `App\User::class`

4) Seed a user
```bash
php artisan lara-ore:user:install
```

5) The majority packages that add models have already admin endpoints. You can check the new endpoints by running

```bash
php artisan route:list
```
| Method   | URI                         | Action                                                        |
|----------|-----------------------------|---------------------------------------------------------------|
| GET,HEAD | api/v1/ore/admin/users      | Railken\LaraOre\Http\Controllers\Admin\UsersController@index  |
| POST     | api/v1/ore/admin/users      | Railken\LaraOre\Http\Controllers\Admin\UsersController@create |
| PUT      | api/v1/ore/admin/users/{id} | Railken\LaraOre\Http\Controllers\Admin\UsersController@update |
| DELETE   | api/v1/ore/admin/users/{id} | Railken\LaraOre\Http\Controllers\Admin\UsersController@remove |
| GET,HEAD | api/v1/ore/admin/users/{id} | Railken\LaraOre\Http\Controllers\Admin\UsersController@show   |

These endpoints are pure and without any middleware. This means that there is not authentication or authorization that will protect these endpoints.

You can add the middleware (e.g. 'auth:api') in the configuration, something like this.

```php
'router' => [
    'middleware' => [
        'auth:api'
    ]
]
```
### Authentication
Of couse we will be using passport, so first we will install it.
```bash
composer require laravel/passport
php artisan migrate
php artisan passport:install
```

The thing is that "BaseUser" has no "authentication" logic. So we have to add that

```php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Railken\LaraOre\User\User as BaseUser;
use Laravel\Passport\HasApiTokens;

class User extends BaseUser implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens;
}
```

You can do this, or use the `lara-ore-auth` package. To to this install the package
```bash
composer require railken/lara-ore-auth
```

```php
use Railken\LaraOre\Concerns\Auth\User as BaseUser;

class User extends BaseUser 
{

}
```
The package will also add some endpoints to sign-in without client_id and client_secret alongs with social login.


### Base
* [User](https://github.com/railken/lara-ore-user)
* [Auth](https://github.com/railken/lara-ore-auth)
* [Account](https://github.com/railken/lara-ore-account)
* [Registration](https://github.com/railken/lara-ore-registration)

### Common
* [Config](https://github.com/railken/lara-ore-config)
* [Taxonomy](https://github.com/railken/lara-ore-taxonomy)
* [Template](https://github.com/railken/lara-ore-template)
* [Work](https://github.com/railken/lara-ore-work)
* [Schedule](https://github.com/railken/lara-ore-schedule)
* [Listener](https://github.com/railken/lara-ore-listener)
* [Disk](https://github.com/railken/lara-ore-disk)
* [File](https://github.com/railken/lara-ore-file)
* [HttpLogger](https://github.com/railken/lara-ore-request-logger) 
* [EventLogger](https://github.com/railken/lara-ore-event-logger)

### Data
* [Address](https://github.com/railken/lara-ore-address)
* [Legal Entity](https://github.com/railken/lara-ore-legal-entity)
* [Tax](https://github.com/railken/lara-ore-tax)
* [Invoice](https://github.com/railken/lara-ore-invoice)
* [Customer](https://github.com/railken/lara-ore-customer)
* [Recurring Service](https://github.com/railken/lara-ore-recurring-service)
* [Contract](https://github.com/railken/lara-ore-contract)
* [Catalogue](https://github.com/railken/lara-ore-catalogue)
