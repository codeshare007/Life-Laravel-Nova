<?php

namespace App\Http\Controllers\Api\v1_1;

use App\Oil;
use App\Avatar;
use App\Recipe;
use App\Remedy;
use App\Ailment;
use App\Category;
use App\BodySystem;
use App\Supplement;
use App\Enums\AilmentType;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\v1_0\DeltaData as DeltaV1_0;

class DeltaData extends DeltaV1_0
{
    public function getData()
    {
        return $this->getStaticData();
        // return [
        //     'data' => [
        //         'oils' => api_resource('OilResource')->collection($this->getOils()),
        //         'blends' => api_resource('BlendResource')->collection($this->getBlends()),
        //         'remedies' => api_resource('RemedyResource')->collection($this->getRemedies()),
        //         'ailments' => api_resource('AilmentResource')->collection($this->getAilments()),
        //         'body_systems' => api_resource('BodySystemResource')->collection($this->getBodySystems()),
        //         'supplements' => api_resource('SupplementResource')->collection($this->getSupplements()),
        //         'recipes' => api_resource('RecipeResource')->collection($this->getRecipes()),
        //         'categories' => api_resource('CategoryResource')->collection($this->getCategories()),
        //     ],
        // ];
    }

    protected function getStaticData()
    {
        if (Carbon::createFromFormat('d-m-Y H:i:s', '13-03-2019 00:00:00') > $this->modifiedSince) {
            return response()->file(resource_path('/static-delta/v1_1/13-03-2019-00-00-00.json'), [
                'Content-Type' => 'application/json',
            ]);
        } else {
            return [
                'data' => [
                    'oils' => [],
                    'blends' => [],
                    'remedies' => [],
                    'ailments' => [],
                    'body_systems' => [],
                    'supplements' => [],
                    'recipes' => [],
                    'categories' => [],
                ],
            ];
        }
    }

    protected function getOils()
    {
        return Oil::with([
            'blendsWith',
            'foundInBlends',
            'foundIn',
            'properties',
            'constituents',
            'sourcingMethods',
            'safetyInformation',
            'usages.ailments',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getRemedies()
    {
        return Remedy::with([
            'bodySystems',
            'relatedRemedies',
            'remedyIngredients',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getAilments()
    {
        return Ailment::where('ailment_type', AilmentType::Ailment)
            ->with([
                'bodySystems',
                'remedies',
                'solutions.solutionable',
                'relatedAilments',
            ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getSupplements()
    {
        return Supplement::with([
            'supplementAilments',
            'supplementIngredients',
            'safetyInformation',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getRecipes()
    {
        return Recipe::with([
            'recipeIngredients',
            'relatedRecipes',
            'categories',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getCategories()
    {
        return Category::with([
            'recipes',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getBodySystems()
    {
        return BodySystem::with([
            'oils.solutionable',
            'blends.solutionable',
            'supplements.solutionable',
            'remedies',
            'ailments',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }
}