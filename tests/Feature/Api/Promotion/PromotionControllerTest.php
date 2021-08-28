<?php

namespace VCComponent\Laravel\Promotion\Test\Feature\Api\Promotion;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Promotion\Entities\Promotion;
use VCComponent\Laravel\Promotion\Test\TestCase;

class PromotionControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_not_get_list_promotion_from_field_required_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_from_field_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'from' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_from_required_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => ''];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_get_list_promotion_with_from_date_by_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create(['created_at' => '01/08/2021'])->toArray();
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'from' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
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
    public function should_not_get_list_promotion_field_from_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'from' => '3/8/2021'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_to_field_required_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => '', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_to_field_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'test', 'to' => date('Y-m-d', strtotime('3-08-2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Undefined variable: field');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_to_required_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => ''];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'Data missing');
    }
    /**
     * @test
     */
    public function should_not_get_list_promotion_field_to_by_admin()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $data = ['field' => 'updated', 'to' => '3/8/2021'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $response->assertStatus(500);
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_to_date_by_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        foreach ($promotions as $promotion) {
            unset($promotion['updated_at']);
            unset($promotion['created_at']);
        }
        $data = ['field' => 'created', 'to' => date('Y-m-d', strtotime('02/08/2021'))];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
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
    public function should_not_get_list_promotions_with_status_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        $data = ['status' => ''];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $this->assertRequired($response, 'The input status is incorrect');
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_status_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create()->toArray();
        factory(Promotion::class, 5)->create(['status' => 2])->toArray();
        $data = ['status' => 1];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
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
    public function should_get_list_promotions_with_constraints_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create();
        $title_constraints = $promotions[0]->title;
        $promotions = $promotions->map(function ($promotion) {
            unset($promotion['created_at']);
            unset($promotion['updated_at']);
            unset($promotion['start_date']);
            return $promotion;
        })->toArray();

        $constraints = '{"title":"' . $title_constraints . '"}';

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions?constraints=' . $constraints);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$promotions[0]],
        ]);
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_search_admin_router()
    {
        $token = $this->loginToken();
        factory(Promotion::class, 5)->create();
        $promotion = factory(Promotion::class)->create(['title' => 'test_promotion'])->toArray();
        unset($promotion['created_at']);
        unset($promotion['updated_at']);
        unset($promotion['start_date']);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions?search=test_promotion');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [$promotion],
        ]);
        $response->assertJsonCount(1, 'data');

    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_order_admin_router()
    {
        $token = $this->loginToken();
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

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions?order_by=' . $order_by);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $promotions,
        ]);
    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_paginate_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create();
        $promotions = $promotions->map(function ($promotion) {
            unset($promotion['created_at']);
            unset($promotion['updated_at']);
            unset($promotion['start_date']);
            return $promotion;
        })->toArray();
        $listId = array_column($promotions, 'id');
        array_multisort($listId, SORT_DESC, $promotions);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions?page=1&?per_page=20');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $promotions,
        ]);
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'pagination' => [
                    'total', 'count', 'per_page', 'current_page', 'total_pages', 'links' => [],
                ],
            ],
        ]);

    }
    /**
     * @test
     */
    public function should_get_list_promotions_with_type_admin_router()
    {
        $token = $this->loginToken();
        $promotions = factory(Promotion::class, 5)->create(['type' => 'products']);
        $promotions_miss = factory(Promotion::class, 5)->create(['type' => 'customer']);
        $promotions = $promotions->map(function ($promotion) {
            unset($promotion['created_at']);
            unset($promotion['updated_at']);
            unset($promotion['start_date']);
            return $promotion;
        })->toArray();
        $listId = array_column($promotions, 'id');
        array_multisort($listId, SORT_DESC, $promotions);
        $data = ['type' => 'products'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $promotions,
        ]);
        $response->assertJsonMissing([
            'data' => $promotions_miss,
        ]);

    }
    /**
     * @test
     */
    public function should_not_delete_promotion_exits_admin_router()
    {
        $token = $this->loginToken();
        $this->assertDatabaseMissing('promotions', ['id' => 1]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', 'api/promotions/1');
        $this->assertExits($response, 'promotion not found');
    }
    /**
     * @test
     */
    public function should_delete_promotion_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();
        unset($promotion['created_at']);
        unset($promotion['updated_at']);
        unset($promotion['start_date']);

        $this->assertDatabaseHas('promotions', $promotion);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', 'api/promotions/' . $promotion['id']);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('promotions', $promotion);

    }
    /**
     * @test
     */
    public function should_not_create_promotion_code_required_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['code' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'code', 'The code field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_start_date_required_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['start_date' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'start_date', 'The start date field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_title_required_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['title' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'title', 'The title field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_status_required_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['status' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'status', 'The status field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_code_unique_by_admin_router()
    {
        $token = $this->loginToken();
        factory(Promotion::class)->create(['code' => 'codetest']);
        $this->assertDatabaseHas('promotions', ['code' => 'codetest']);
        $data = factory(Promotion::class)->make(['code' => 'codetest'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'code', 'The code has already been taken.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_start_date_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['start_date' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'start_date', 'The start date is not a valid date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_end_date_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['end_date' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'end_date', 'The end date is not a valid date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_end_date_after_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['start_date' => '2/1/2021', 'end_date' => '1/1/2021'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'end_date', 'The end date must be a date after or equal to start date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_status_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['status' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'status', 'The status must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_id_type_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['promo_type_id' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'promo_type_id', 'The promo type id must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_id_type_not_exits_by_admin_router()
    {
        $token = $this->loginToken();
        $this->assertDatabaseMissing('promotion_type', ['id' => 1]);
        $data = factory(Promotion::class)->make(['promo_type_id' => 1])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'promo_type_id', 'The selected promo type id is invalid.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_value_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['promo_value' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'promo_value', 'The promo value must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_create_promotion_quantity_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make(['quantity' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $this->assertValidator($response, 'quantity', 'The quantity must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_create_promotion_by_admin_router()
    {
        $token = $this->loginToken();
        $data = factory(Promotion::class)->make()->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', 'api/promotions', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_get_promotion_exits_admin_router()
    {
        $token = $this->loginToken();
        $this->assertDatabaseMissing('promotions', ['id' => 1]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions/1');
        $this->assertExits($response, 'Promotions not found');
    }
    /**
     * @test
     */
    public function should_get_promotion_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();
        unset($promotion['created_at']);
        unset($promotion['updated_at']);
        unset($promotion['start_date']);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', 'api/promotions/' . $promotion['id']);
        $response->assertStatus(200);
        $response->assertJson(['data' => $promotion]);
    }
    /**
     * @test
     */

    public function should_not_update_promotion_not_exits_admin_router()
    {
        $token = $this->loginToken();
        $this->assertDatabaseMissing('promotions', ['id' => 1]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/1');
        $this->assertExits($response, 'promotion not found');
    }
    /**
     * @test
     */

    public function should_not_update_promotion_code_exits_admin_router()
    {
        $token = $this->loginToken();
        factory(Promotion::class)->create(['code' => 'codetest'])->toArray();
        $this->assertDatabaseHas('promotions', ['code' => 'codetest']);
        $promotion = factory(Promotion::class)->create()->toArray();
        $data = ['code' => 'codetest'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'code', 'The code has already been taken.');

    }
    /**
     * @test
     */
    public function should_not_update_promotion_code_required_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();
        $data = factory(Promotion::class)->make(['code' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'code', 'The code field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_start_date_required_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['start_date' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'start_date', 'The start date field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_title_required_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['title' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'title', 'The title field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_status_required_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['status' => ''])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'status', 'The status field is required.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_start_date_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['start_date' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'start_date', 'The start date is not a valid date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_end_date_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['end_date' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'end_date', 'The end date is not a valid date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_end_date_after_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['start_date' => '2/1/2021', 'end_date' => '1/1/2021'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'end_date', 'The end date must be a date after or equal to start date.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_status_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['status' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'status', 'The status must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_id_type_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['promo_type_id' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'promo_type_id', 'The promo type id must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_id_type_not_exits_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $this->assertDatabaseMissing('promotion_type', ['id' => 1]);
        $data = factory(Promotion::class)->make(['promo_type_id' => 1])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'promo_type_id', 'The selected promo type id is invalid.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_value_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['promo_value' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'promo_value', 'The promo value must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_not_update_promotion_quantity_by_admin_router()
    {
        $token = $this->loginToken();
        $promotion = factory(Promotion::class)->create()->toArray();

        $data = factory(Promotion::class)->make(['quantity' => 'test'])->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $this->assertValidator($response, 'quantity', 'The quantity must be a number.');
        $this->assertDatabaseMissing('promotions', $data);
    }
    /**
     * @test
     */
    public function should_update_contact_form_admin()
    {
        $token = $this->loginToken();

        $promotion = factory(Promotion::class)->create();
        unset($promotion['updated_at']);
        unset($promotion['created_at']);
        $promotion->title = "update title";
        $data = $promotion->toArray();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', 'api/promotions/' . $promotion['id'], $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => $data['title'],
        ]);
        $this->assertDatabaseHas('promotions', $data);
    }
}
