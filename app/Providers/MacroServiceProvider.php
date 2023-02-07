<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('getRouteNameForModel', function ($className) {
            $modelName = Str::after($className, 'App\\');
            return Str::camel((Str::plural($modelName)));
        });

        Relation::macro('getPivotColumns', function () {
            return $this->pivotColumns;
        });

        Relation::macro('syncBothWays', function ($ids, $detaching = true) {

            $relationName = $this->relationName;
            $changes = $this->sync($ids);
            $parentModelId = $this->parent->id;
            $parentModelClass = get_class($this->parent);
            
            if ($changes['attached']) {
                foreach($changes['attached'] as $id) {
                    $parentModelClass::find($id)->$relationName()->attach($parentModelId);
                }
            }

            if ($changes['detached']) {
                foreach($changes['detached'] as $id) {
                    $parentModelClass::find($id)->$relationName()->detach($parentModelId);
                }
            }

            return $changes;
        });

        Collection::macro('mapToOptions', function () {
            return $this->map(function($value, $key){
                return ['label' => $value, 'value' => $key];
            })->values();
        });
    }
}
