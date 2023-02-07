<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Oil;
use App\Blend;
use App\Remedy;
use App\Ailment;
use App\BodySystem;
use App\Enums\AilmentType;
use Illuminate\Support\Carbon;

class DeltaData
{
    protected $modifiedSince;

    public function __construct(Carbon $modifiedSince)
    {
        $this->modifiedSince = $modifiedSince;
    }

    public function getData()
    {
        return [
            'data' => [
                'oils' => api_resource('OilResource')->collection($this->getOils()),
                'blends' => api_resource('BlendResource')->collection($this->getBlends()),
                'remedies' => api_resource('RemedyResource')->collection($this->getRemedies()),
                'ailments' => api_resource('AilmentResource')->collection($this->getAilments()),
                'body_systems' => api_resource('BodySystemResource')->collection($this->getBodySystems()),
            ],
        ];
    }

    protected function getOils()
    {
        return Oil::with([
            'blendsWith',
            'foundInBlends',
            'properties',
            'sourcingMethods',
            'safetyInformation',
            'usages.ailments',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getBlends()
    {
        return Blend::with([
            'ingredients',
            'safetyInformation',
            'usages.ailments',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getRemedies()
    {
        return Remedy::with([
            'bodySystems',
            'relatedRemedies',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getAilments()
    {
        return Ailment::where('ailment_type', AilmentType::Ailment)
            ->with([
                'bodySystems',
                'remedies',
                'oils',
                'blends',
                'relatedAilments',
            ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }

    protected function getBodySystems()
    {
        return BodySystem::with([
            'solutions',
            'remedies',
            'ailments',
        ])->where('updated_at', '>=', $this->modifiedSince)->orderBy('name')->get();
    }
}