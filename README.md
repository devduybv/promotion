- [Configuration](#configuration)
  - [Model and Transformer](#model-and-transformer)
  - [Auth middleware](#auth-middleware)
- [Routes](#routes)

## Configuration
### Model and Transformer
You can use your own model and transformer class by modifying the configuration file `config\promotion.php`

```php
'models'          => [
    'promotion' => App\Entities\Promotion::class,
],

'transformers'    => [
    'promotion' => App\Transformers\PromotionTransformer::class,
],
```
### Auth middleware
Configure auth middleware in configuration file `config\promotion.php`

```php
'auth_middleware' => [
        'admin'    => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
        'frontend' => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
],
```
## Routes

The api endpoint should have these format:
| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/promotions                               |
| GET    | /api/promotions/{id}                          |
| POST   | /api/promotions                               |
| PUT    | /api/promotions/{id}                          |
| DELETE | /api/promotions/{id}                          |


