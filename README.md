- [Configuration](#configuration)
  - [Model and Transformer](#model-and-transformer)
  - [Auth middleware](#auth-middleware)
- [Facades](#facades)
  - [Use](#use)
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
### Use
Check the code during use and have active status
```php
Promotion::check($code);
```
Check if the promo code exists
```php
Promotion::isExist($code);
```
Check the activated promo code
```php
Promotion::isAvailable($code);
```
Check expired promo code
```php
Promotion::isExpired($code);
```
Check promo code has started
```php
Promotion::isStarted($code);
```
Find promotions by promo code
```php
Promotion::findByCode($code);
```
Parameters

| name  | type | description | required? |
| ----- | ---- | ----------- | --------- |
| $code | string | Promo code | Yes |

Get the promotion list of 1 product or 1 user
```php
Promotion::getPromoRelation($id, $promo_type = 'users', array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Get the promotion list of 1 product or 1 user with paginate 
```php
Promotion::getPromoRelationPaginate($id, $promo_type = 'users', array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Parameters
| name  | type | description | default | required? |
| ----- | ---- | ----------- | ------- | --------- |
| $id | integer | User or product id |  | Yes |
| $promo_type | string | Promotion type of products or users | 'users' | NO |
| $where | array | Array of conditions  | [] | NO |
| $number | integer | Take out the number of promo codes (If $number = 0 then take all promotions)  | 10 | NO |
| $order_by | string | Sort by field  | 'id' | NO |
| $order | string | Sort type (desc,asc)  | 'desc' | NO |

Get a list of products or users that are eligible for this promo code
```php
Promotion::withRelation($value, $column = 'code', $relations = 'products', array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Get a list of products or users whose pagination is applied to this promo code
```php
Promotion::withRelationPaginate($value, $column = 'code', $relations = 'products', array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Parameters
| name  | type | description | default | required? |
| ----- | ---- | ----------- | ------- | --------- |
| $value | string | Value to find promo code |  | Yes |
| $column | string | Find promotional value by field | 'code' | NO |
| $relations | string | Relationship of promo code (products,users) | 'products' | NO |
| $where | array | Array of conditions  | [] | NO |
| $number | integer | Take out the number of promo codes (If $number = 0 then take all promotions)  | 10 | NO |
| $order_by | string | Sort by field  | 'id' | NO |
| $order | string | Sort type (desc,asc)  | 'desc' | NO |

Returns a list of promo codes according to 1 condition
```php
Promotion::where($column, $value);
```
Parameters
| name  | type | description | required? |
| ----- | ---- | ----------- | --------- |
| $column | string | Find promotional value by field | NO |
| $value | string | Value to find promo code | Yes |

Returns a list of promo codes under multiple conditions
```php
Promotion::findByWhere(array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Returns a paginated list of promo codes according to multiple conditions
```php
Promotion::findByWherePaginate(array $where = [], $number = 10, $order_by = 'id', $order = 'desc');
```
Parameters
| name  | type | description | default | required? |
| ----- | ---- | ----------- | ------- | --------- |
| $where | array | Array of conditions  | [] | NO |
| $number | integer | Take out the number of promo codes (If $number = 0 then take all promotions)  | 10 | NO |
| $order_by | string | Sort by field  | 'id' | NO |
| $order | string | Sort type (desc,asc)  | 'desc' | NO |

Search promotions by keyword
```php
Promotion::getSearchResult($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*']);
```
Search promotions with paging by keywords
```php
Promotion::getSearchResultPaginate($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*']);
```
Parameters
| name  | type | description | default | required? |
| ----- | ---- | ----------- | ------- | --------- |
| $key_word | string | Search keyword  |  | YES |
| $list_field | array | Array of Search Fields  | ['title'] | NO |
| $where | array | Array of conditions  | [] | NO |
| $number | integer | Take out the number of promo codes (If $number = 0 then take all promotions)  | 10 | NO |
| $order_by | string | Sort by field  | 'id' | NO |
| $order | string | Sort type (desc,asc)  | 'desc' | NO |
| $columns | array | Return fields  | ['*'] | NO |

## Routes

The api endpoint should have these format:
| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/admin/promotions                               |
| GET    | /api/admin/promotions/{id}                          |
| POST   | /api/admin/promotions                               |
| PUT    | /api/admin/promotions/{id}                          |
| DELETE | /api/admin/promotions/{id}                          |
| GET    | /api/admin/promotions/check                         |
| ----   | ----                                                |
| GET    | /api/promotions                                     |
| GET    | /api/promotions/{type}/{id}                         |
| GET    | /api/promotions/check                               |



