<?php

namespace Tests\Feature\Api\v3_0;

use App\Oil;
use App\Recipe;
use App\Remedy;
use Tests\TestCase;
use App\Enums\Region;
use App\RegionalName;
use App\Enums\TagType;
use App\Enums\SearchModels;
use App\Enums\RegionableModels;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\Search\SearchController;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_each_searchable_model_appears_in_search_results()
    {
        $modelUUIDS = collect(SearchModels::getValues())->map(function ($modelClass) {
            $attributes = ['name' => 'Anxiety'];
            if ($modelClass === SearchModels::Tag()->value) {
                $attributes['type'] = TagType::Property;
            }

            return factory($modelClass)->create($attributes)->uuid;
        });

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx',
        ]);

        $compare = $modelUUIDS->diff(collect($results->getData())->flatten()->pluck('uuid')->all());
        $this->assertTrue(
            $compare->isEmpty()
        );
    }

    public function test_ugc_can_be_excluded_from_search_results()
    {
        $name = [
            'name' => 'Anxiety'
        ];

        $modelUUIDS = collect([]);
        $modelUUIDS->push(factory(Oil::class)->create($name)->fresh());
        $modelUUIDS->push(factory(Recipe::class)->create($name)->fresh());
        $modelUUIDS->push(factory(Remedy::class)->create($name)->fresh());

        $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx', 'ugc' => false
        ])->assertJsonCount(1);

        $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx',
        ])->assertJsonCount(1);
    }

    public function test_ugc_search_results_contain_user_id()
    {
        $name = [
            'name' => 'Anxiety',
            'user_id' => $this->user->id
        ];

        $modelUUIDS = collect([]);
        $modelUUIDS->push(factory(Recipe::class)->create($name)->fresh());

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx', 'ugc' => true
        ]);

        $this->assertTrue(
            collect($results->getData())->flatten()->pluck('user_id')->first() == $this->user->id
        );

        $modelUUIDS->push(factory(Remedy::class)->create($name)->fresh());

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx', 'ugc' => true
        ]);

        $this->assertTrue(
            collect($results->getData())->flatten()->pluck('user_id')->unique()->first() == $this->user->id
        );
    }

    public function test_items_searchable_by_regional_name()
    {
        $this->withoutExceptionHandling();

        // set user to different region
        $this->user->update(['region_id' => Region::Canada()]);

        // add regional name for region
        $modelUUIDS = collect(RegionableModels::getValues())->map(function ($modelClass) {
            $model = factory($modelClass)->create([
                'name' => 'Anxiety'
            ]);

            factory(RegionalName::class)->create([
                'name' => 'Canada',
                'region_id' => Region::Canada()->value,
                'regionable_type' => $modelClass,
                'regionable_id' => $model->id,
            ]);

            return $model->uuid;
        });

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'anx',
        ])->assertSuccessful();

        $compare = $modelUUIDS->diff(collect($results->getData())->flatten()->pluck('uuid')->all());
        $this->assertTrue(
            $compare->isEmpty()
        );

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'canada',
        ])->assertSuccessful();

        $compare = $modelUUIDS->diff(collect($results->getData())->flatten()->pluck('uuid')->all());
        $this->assertTrue(
            $compare->isEmpty()
        );

        // user no region should not be able to search by regional name
        $this->user->update(['region_id' => null]);

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'canada',
        ])->assertSuccessful();

        $this->assertTrue(
            collect($results->getData())->flatten()->isEmpty()
        );

        // user different region
        $this->user->update(['region_id' => Region::China()->value]);

        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'canada',
        ])->assertSuccessful();

        $this->assertTrue(
            collect($results->getData())->flatten()->isEmpty()
        );
    }

    public function test_search_returns_no_results_for_queries_that_are_less_than_the_min_character_limit()
    {
        $results = $this->json('POST', action([SearchController::class, 'index'], 'en'), [
            'search' => 'an',
        ]);

        $this->assertTrue(
            collect($results->getData())->flatten()->isEmpty()
        );
    }
}
