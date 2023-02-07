<?php

namespace App\Traits;

use App\Recipe;
use App\RegionalName;
use App\Remedy;
use \Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

trait RegionableTrait
{
    public function regionalNames()
    {
        return $this->morphMany(RegionalName::class, 'regionable');
    }

    /**
     * Override the name attribute to fetch
     * a regional name based on the users
     * current region
     */
    public function getNameAttribute($value)
    {
        if (!request()->wantsJson()) {
            return $value;
        }

        // check the API guard as we dont want
        // to manipulate the name in the CMS
        $user = auth('api')->user();
        if ($user && $user->region_id) {
            $regionalName = $this->regionalNames
                ->where('region_id', $user->region_id)
                ->first();
            if ($regionalName) {
                return $regionalName->name;
            }
        }

        return $value;
    }

    public function hasRegionalName()
    {
        $user = auth('api')->user();
        return $user->region_id;
    }

    public function byRegionableName($name, $regionId): Builder
    {
        $ugcTables = [
            (new Recipe())->getTable(),
            (new Remedy())->getTable(),
        ];

        $columns = ['regional_names.id', 'uuid', 'regional_names.name'];

        if (in_array($this->getTable(), $ugcTables)) {
            $columns[] = $this->getTable() . '.user_id';
        }

        return DB::table($this->getTable())
            ->select($columns)
            ->join('regional_names', function ($join) use ($name, $regionId) {
                $join->on($this->getTable() . '.id', '=', 'regional_names.regionable_id')
                    ->where('regional_names.name', 'LIKE', '%' . $name . '%')
                    ->where('regional_names.region_id', $regionId)
                    ->where('regional_names.regionable_type', $this->fullModelName()->name);
            });
    }
}
