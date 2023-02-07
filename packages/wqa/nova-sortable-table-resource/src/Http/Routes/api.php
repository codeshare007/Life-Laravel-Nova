<?php

Route::patch('{resource}/{resourceId}/reorder', 'Wqa\NovaSortableTableResource\Http\Controllers\ResourceSortableController@handle');
Route::post('{resource}/batch-update', 'Wqa\NovaSortableTableResource\Http\Controllers\ResourceUpdateController@handle');
Route::post('{resource}/field-inline-save', 'Wqa\NovaSortableTableResource\Http\Controllers\ResourceUpdateController@handle');
Route::get('{resource}/populate-usage-options', 'Wqa\NovaSortableTableResource\Http\Controllers\UsageOptionsController@handle');
