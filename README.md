- [Configuration](#configuration)
  - [Model and Transformer](#model-and-transformer)
  - [Auth middleware](#auth-middleware)
- [Facades](#facades)
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

## Facades
Supporting facade functions
use
```php
Promotion::functionName();
```
function list
```php
public function check($code)
// check the code during use and have active status

public function findByCode($code)
// find promo codes by code

public function withRelationPaginate($column = '', $value = '', 
$relations = 'products', $perPage = 5)
// find the code by condition and return a list of products or customers that can apply this code

public function where($column, $value)
// return promotion list under 1 condition

public function findByWhere(array $where, $number = 10, $order_by = 'id', 
$order = 'desc')
// return promotion list under multiple conditions

public function findByWherePaginate(array $where, $number = 10, $order_by = 'id', $order = 'desc')
// return promotion list on multiple conditions with pagination

public function getSearchResult($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*'])
// Search for promotions by key word

public function getSearchResultPaginate($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*'])
// Search for promotions by key word with pagination
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


