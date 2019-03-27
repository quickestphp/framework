# Quickest Router

This router package is easy, fast and extremely uncomplicated. It is not made for a specific FRAMEWORK, you can add it to any of your projects. Create and manage your routes in minutes!

## How to install it

First create the folder where your application will live and then inside of it use the command for requiring the package or define in composer.json file, **IT IS NECESSARY** to have **COMPOSR** in order to **INSTALL** this package, see bellow:

```bash
$ composer require quickestphp/router
```

or add it to composer.json

```bash
"quickestphp/router": "^1.0"
```

## How to use it

Setting up Quickest router is very easy

> In order to get a Quickest Router instance you have
```php
$router = new Quickest\Router;
```

> After it is necessary to define the routes and its handlers, the SUPPORTED VERBS ARE:
- GET : to navigate between pages and read records
- POST : is used for inserting records
- PUT / PATCH : this verb is responsable for updating
- DELETE : used for removing records
- GROUP : groups a collection of routes under a path

> To actually execute the routing system, after all definded routes is mandatory to call the **dispatch method** at **Router::dispatch()**

```php
$router->dispatch();
```

## Contributing

Please see [CONTRIBUTING](https://github.com/quickestphp/router/blob/master/CONTRIBUTING.md) for details.

## Support

Security: If you discover any security related issues, please email support@quickestphp.org instead of using the issue tracker.

Thank you

## Credits

- [Jonatan Flores](https://github.com/jonatanflores) (Developer)
- [All Contributors](https://github.com/quickestphp/router/contributors) (This Rock)

## License

The MIT License (MIT). Please see [License File](https://github.com/quickestphp/router/blob/master/LICENSE) for more information.