<?php

Route::redirect('/', '/admin', 301);
//Route::redirect('/login', 'admin/login', 301);
Route::get('/admin/login', '\Wqa\NovaExtendResources\Http\Controllers\Nova\LoginController@showLoginForm');
Route::post('/admin/login', '\Wqa\NovaExtendResources\Http\Controllers\Nova\LoginController@login')
    ->name('nova.login');

Route::middleware('nova')->group(function () {
    Route::get('/download-users-for-klaviyo', DownloadUsersForKlaviyoController::class);
});


// Mail preview
// Route::get('mail', function() {

//     $message = (new App\Notifications\Mail\PasswordResetRequest('dummyToken', 'dummywefwefewfwefhwefhwefiuhewufhweiuhfiuwehfuwehfiuwehfiuwehfiuwehfuihweifhbwivbweivbiuwenfoqwfjweiofhwebfowefwoeiufj@email.com'))->toMail(null);
//     $markdown = resolve(Illuminate\Mail\Markdown::class);

//     return $markdown->render($message->markdown, $message->data());
// });

// Route::get('/pdf-api', function () {

//     $ilovepdf = new \Ilovepdf\Ilovepdf('project_public_2b64656184257950f45b253bf6d41c70_P_-_77406bd3a37da479b31b09baca48ce5cb','secret_key_5f33fe11807ba9fce0b6ece6fe29b3fd_sI4r5909d8ee43da8436f6fc3e6cee63295c9');
//     $myTask = $ilovepdf->newTask('extract');
//     $file1 = $myTask->addFile(storage_path('4 Body Systems.pdf'));
//     $myTask->setDetailed(true);
//     $myTask->execute();
//     $myTask->download();

//     return [1,2,3,4,5];
// });

// Route::get('/pdf-extract', function(\Wqa\PdfExtract\File $file){

//     $file->setName('5th Edition Essential Life (Body Systems).pdf');

//     $this->pdf = new \Wqa\PdfExtract\PdfExtract();
//     $this->pdf->execute($file);

//     $results = $this->pdf->extract([
//         'page' => request('page'),
//         'area' => request('area')
//     ]);

//     return $results;
// });

// Route::get('/csv', function () {
//     $records = App\Ailment::with('bodySystems')->get();

//     //load the CSV document from a string
//     $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());

//     //insert the header
//     $csv->insertOne(array_merge(\Schema::getColumnListing('ailments'), [
//         'body_systems'
//     ]));

//     //insert all the records
//     $records->each(function($record) use ($csv) {
//         $csv->insertOne([
//             $record->id,
//             $record->created_at,
//             $record->updated_at,
//             $record->name,
//             $record->image_url,
//             $record->color,
//             $record->short_description,
//             $record->is_featured,
//             $record->sort_order,
//             App\Enums\AilmentType::getDescription($record->ailment_type),
//             $record->bodySystems->pluck('name')->implode(', '),
//         ]);
//     });

//     $csv->output('ailments.csv');
// });
