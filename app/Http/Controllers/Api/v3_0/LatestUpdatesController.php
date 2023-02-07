<?php

namespace App\Http\Controllers\Api\v3_0;

use Carbon\Carbon;
use App\DeletedElement;
use Illuminate\Http\Request;
use App\Enums\v3_0\ElementType;
use App\Http\Controllers\Controller;

class LatestUpdatesController extends Controller
{
    protected $modifiedSince;

    public function __invoke(Request $request)
    {
        $this->modifiedSince = $request->get('modifiedSince', Carbon::now()->toDateTimeString());

        $updatedAndDeletedUuids = array_merge($this->updatedUuids(), $this->deletedUuids());

        return response()->json([
            'data' => array_slice($updatedAndDeletedUuids, 0, 101),
        ]);
    }

    protected function updatedUuids(): array
    {
        return collect(ElementType::toArray())
            ->map(function (string $modelName) {
                return (new $modelName)
                    ->withoutGlobalScopes()
                    ->select('uuid')
                    ->where('updated_at', '>=', $this->modifiedSince)
                    ->get();
            })
            ->flatten()
            ->pluck('uuid')
            ->toArray();
    }

    protected function deletedUuids(): array
    {
        return DeletedElement::select('id')
            ->where('updated_at', '>=', $this->modifiedSince)
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
