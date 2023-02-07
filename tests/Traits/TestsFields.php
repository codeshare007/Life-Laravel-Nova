<?php

namespace Tests\Traits;

trait TestsFields
{
    protected function assertHasField(string $modelClass, string $field)
    {
        $model = factory($modelClass)->create();

        $this->assertDatabaseHas($model->getTable(), [
            $field => $model->{$field},
        ]);
    }
}
