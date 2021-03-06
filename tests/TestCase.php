<?php

namespace VCComponent\Laravel\Promotion\Test;

use Dingo\Api\Provider\LaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Post\Entities\Post;
use VCComponent\Laravel\Post\Providers\PostComponentProvider;
use VCComponent\Laravel\Promotion\Providers\PromotionServiceProvider;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Providers\UserComponentEventProvider;
use VCComponent\Laravel\User\Providers\UserComponentProvider;
use VCComponent\Laravel\User\Providers\UserComponentRouteProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return \VCComponent\Laravel\Post\Providers\PostComponentProvider
     */
    protected function getPackageProviders($app)
    {

        return [
            PromotionServiceProvider::class,
            LaravelServiceProvider::class,
            \Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
            \Illuminate\Auth\AuthServiceProvider::class,
            UserComponentEventProvider::class,
            UserComponentProvider::class,
            UserComponentRouteProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../src/database/factories');
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');

    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('promotion.models', [
            'post' => \VCComponent\Laravel\Promotion\Entities\Promotion::class,
        ]);
        $app['config']->set('post.transformers', [
            'post' => \VCComponent\Laravel\Promotion\Transformers\PromotionTransformer::class,
        ]);
        $app['config']->set('promotion.auth_middleware', [
            'admin' => [
                [
                    'middleware' => '',
                    'except' => [],
                ],
            ],
            'frontend' => [
                'middleware' => '',
                'except' => [],
            ],
        ]);
        $app['config']->set('api', [
            'standardsTree' => 'x',
            'subtype' => '',
            'version' => 'v1',
            'prefix' => 'api',
            'domain' => null,
            'name' => null,
            'conditionalRequest' => true,
            'strict' => false,
            'debug' => true,
            'errorFormat' => [
                'message' => ':message',
                'errors' => ':errors',
                'code' => ':code',
                'status_code' => ':status_code',
                'debug' => ':debug',
            ],
            'middleware' => [
            ],
            'auth' => [
            ],
            'throttling' => [
            ],
            'transformer' => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat' => 'json',
            'formats' => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions' => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size' => 2,
                ],
            ],
        ]);
        $app['config']->set('jwt.secret', '5jMwJkcDTUKlzcxEpdBRIbNIeJt1q5kmKWxa0QA2vlUEG6DRlxcgD7uErg51kbBl');
        $app['config']->set('auth.providers.users.model', \VCComponent\Laravel\User\Entities\User::class);
        $app['config']->set('user', ['namespace' => 'user-management']);
        $app['config']->set('repository.cache.enabled', false);

    }
    public function assertExits($response, $error_message)
    {
        $response->assertStatus(400);
        $response->assertJson([
            'message' => $error_message,
        ]);
    }
    public function assertValidator($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => "The given data was invalid.",
            "errors" => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
    public function assertRequired($response, $error_message)
    {
        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => $error_message,
        ]);
    }
    protected function loginToken()
    {

        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user = factory(User::class)->make($dataLogin);
        $user->save();
        $login = $this->json('POST', 'api/user-management/login', $dataLogin);
        $token = $login->Json()['token'];
        return $token;

    }
}
