<?php

namespace App\Http\Controllers\Api\v1_2;

use App\Tag;
use App\Remedy;
use App\Ailment;
use App\Category;
use App\BodySystem;
use App\Enums\AilmentType;
use App\Http\Controllers\Api\v1_1\DeltaData as DeltaV1_1;

class DeltaData extends DeltaV1_1
{
    public function getData()
    {
        return [
            'data' => [
                'oils' => api_resource('OilResource')->collection($this->getOils()),
                'blends' => api_resource('BlendResource')->collection($this->getBlends()),
                'remedies' => api_resource('RemedyResource')->collection($this->getRemedies()),
                'ailments' => api_resource('AilmentResource')->collection($this->getAilments()),
                'symptoms' => api_resource('SymptomResource')->collection($this->getSymptoms()),
                'body_systems' => api_resource('BodySystemResource')->collection($this->getBodySystems()),
                'supplements' => api_resource('SupplementResource')->collection($this->getSupplements()),
                'recipes' => api_resource('RecipeResource')->collection($this->getRecipes()),
                'categories' => api_resource('CategoryResource')->collection($this->getCategories()),
                'properties' => api_resource('PropertyTagResource')->collection($this->getProperties()),
            ],
        ];
    }

    protected function getCategories()
    {
        return Category::with([
            'recipes',
            'topTips',
            'panels',
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
            'symptoms',
            'properties',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getAilments()
    {
        return Ailment::where('ailment_type', AilmentType::Ailment)->with([
            'bodySystems',
            'remedies',
            'solutions.solutionable',
            'secondarySolutions.solutionable',
            'relatedAilments',
            'relatedBodySystems',
            'symptoms',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getSymptoms()
    {
        return Ailment::where('ailment_type', AilmentType::Symptom)->with([
            'bodySystems',
            'remedies',
            'solutions.solutionable',
            'secondarySolutions.solutionable',
            'relatedAilments',
            'relatedBodySystems',
            'symptoms',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getProperties()
    {
        return Tag::with([
            'oils' => function($query) {
                $query->orderBy('name');
            },
        ])->where('updated_at', '>=', $this->modifiedSince)->get();
    }

    protected function getRemedies()
    {
        return Remedy::with([
            'bodySystems',
            'relatedRemedies',
            'remedyIngredients',
            'ailment',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }
}