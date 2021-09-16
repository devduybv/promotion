<?php

namespace VCComponent\Laravel\Promotion\Test\Feature\Api\Promotion;

use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Promotion\Entities\Promotion;
use VCComponent\Laravel\Promotion\Entities\Promotionable;
use VCComponent\Laravel\Promotion\Test\TestCase;

class PromotionFontEndTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_not_get_list_promotion_from_field_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_from_field_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_from_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => ''];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_get_list_promotion_with_from_date_by_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create(['created_at' => '01/08/2021'])->toArray();
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'from' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $promotions,
        ]);
        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_from_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => '3/8/2021'];
        $response = $this->json('GET', 'api/promotions', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_to_field_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_to_field_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_to_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => ''];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_to_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => '3/8/2021'];
        $response = $this->json('GET', 'api/promotions', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_to_date_by_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'to' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->json('GET', 'api/promotions', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $promotions,
        ]);
        $response->assertStatus(200);

    }
    /**
     * @test
     */
    public function should_not_get_list_promotions_with_status_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        $data = ['status' => ''];
        $response = $this->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'The input status is incorrect');
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_status_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        factory(Promotion::class, 5)->create(['status' => 2])->toArray();
        $data = ['status' => 1];
        $response = $this->json('GET', 'api/promotions', $data);
        $response->assertJsonFragment([
            'status' => 1,
        ]);
        $response->assertJsonMissing([
            'status' => 2,
        ]);

    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_constraints_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create();
        $title_constraints = $promotions[0]->title;
        $promotions = $promotions->map(function ($promotion) {
            unset($promotion['created_at']);
            unset($promotion['updated_at']);
            unset($promotion['start_date']);
            return $promotion;
        })->toArray();

        $constraints = '{"title":"' . $title_constraints . '"}';

        $response = $this->json('GET', 'api/promotions?constraints=' . $constraints);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$promotions[0]],
        ]);
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_search_fontend_router()
    {
        factory(Promotion::class, 5)->create();
        $promotion = factory(Promotion::class)->create(['title' => 'test_promotion'])->toArray();
        unset($promotion['created_at']);
        unset($promotion['updated_at']);
        unset($promotion['start_date']);
        $response = $this->json('GET', 'api/promotions?search=test_promotion');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$promotion],
        ]);
        $response->assertJsonCount(1, 'data');

    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_order_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create();
        $promotions = $promotions->map(function ($promotion) {
            unset($promotion['created_at']);
            unset($promotion['updated_at']);
            unset($promotion['start_date']);
            return $promotion;
        })->toArray();
        $order_by = '{"id":"desc"}';
        $listId = array_column($promotions, 'id');
        array_multisort($listId, SORT_DESC, $promotions);

        $response = $this->json('GET', 'api/promotions?order_by=' . $order_by);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $promotions,
        ]);
    }
    /**
     * @test
     */
    public function should_not_check_promotion_code_field_required_by_fontend()
    {
        $data = ['code' => ''];
        $response = $this->json('GET', 'api/promotions/check', $data);
        $this->assertValidator($response, 'code', 'The code field is required.');
    }
    /**
     * @test
     */
    public function should_not_check_promotion_code_not_exits_by_fontend()
    {
        $this->assertDatabaseMissing('promotions', ['code' => 'test']);
        $data = ['code' => 'test'];
        $response = $this->json('GET', 'api/promotions/check', $data);
        $this->assertExits($response, 'Promotion not found');

    }
    /**
     * @test
     */
    public function should_not_check_promotion_code_not_active_by_fontend()
    {
        $promotion = factory(Promotion::class)->create(['status' => 2]);
        $data = ['code' => $promotion->code];
        $response = $this->json('GET', 'api/promotions/check', $data);
        $this->assertRequired($response, 'Promo code has not been activated');
    }
    /**
     * @test
     */
    public function should_not_check_promotion_date_expired_by_fontend()
    {
        $promotion = factory(Promotion::class)->create(['end_date' => date('Y-m-d', strtotime('09/09/2021'))]);
        $data = ['code' => $promotion->code];
        $response = $this->json('GET', 'api/promotions/check', $data);
        $this->assertRequired($response, 'Promo code has expired');
    }
    /**
     * @test
     */
    public function should_not_check_promotion_date_not_start_by_fontend()
    {
        $promotion = factory(Promotion::class)->create(['start_date' => new DateTime('tomorrow')]);
        $data = ['code' => $promotion->code];
        $response = $this->json('GET', 'api/promotions/check', $data);
        $this->assertRequired($response, 'Promo code has not started yet');

    }

    /**
     * @test
     */
    public function should_not_get_promotion_relation_from_field_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_from_field_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_field_from_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => ''];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_get_promotion_relation_with_from_date_by_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create(['created_at' => '2021/09/14'])->toArray();
        factory(Promotionable::class)->create(['promo_id' => 1, 'promoable_id' => 1]);
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'from' => date('Y-m-d', strtotime('2021/09/15'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $promotions,
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_field_from_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => '3/8/2021'];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_to_field_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_to_field_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_field_to_required_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => ''];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_not_get_promotion_relation_field_to_by_fontend()
    {
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => '2021/09/13'];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */

    public function should_get_list_promotions_relation_with_to_date_by_fontend_router()
    {
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        factory(Promotionable::class)->create(['promo_id' => 1, 'promoable_id' => 1]);
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'to' => date('Y-m-d', strtotime('2021/09/13'))];
        $response = $this->json('GET', 'api/promotions/users/1', $data);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJsonMissing([
            'data' => $promotions,
        ]);
        $response->assertStatus(200);

    }

}
