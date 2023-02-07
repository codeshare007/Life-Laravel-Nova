<?php

use App\Element;
use App\Enums\ElementType;
use App\UserGeneratedContent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddElementData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get list of existing data
        $elements = Element::all()->groupBy('element_type');

        // add UGC content
        $uuidData = array_merge(['UserUserGeneratedContent' => UserGeneratedContent::class], ElementType::toArray());

        foreach ($uuidData as $elementType) {
            (new $elementType)->all()
                ->whereNotIn(
                    'id',
                    isset($elements[$elementType]) ?
                        $elements[$elementType]->pluck('element_id') :
                        []
                )
                ->map(function ($newElement) use ($elementType) {
                    $uuid = (string) Str::uuid();
                    $data = [];
                    $data['global'] = [
                        'id' => $uuid,
                        'element_type' => $elementType,
                        'element_id' => $newElement->id,
                        'created_at' => Carbon::now()
                    ];

                    $data['model'] = [
                        'id' => $newElement->id,
                        'uuid' => $uuid
                    ];

                    return $data;
                })->chunk(100)->each(function ($insertElements) use ($elementType) {
                    print_r('Adding ' .
                        count($insertElements) .
                        ' ' .
                        (new $elementType)->getTable() . ' to global elements' .
                        PHP_EOL
                    );

                    \DB::table('global_elements')->insert($insertElements->pluck('global')->all());
                    foreach ($insertElements->pluck('model') as $modelData) {
                        \DB::table((new $elementType)->getTable())
                            ->where('id', $modelData['id'])
                            ->update(['uuid' => $modelData['uuid']]);
                    }
                });
        }
    }
}
