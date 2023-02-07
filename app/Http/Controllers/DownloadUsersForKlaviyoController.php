<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Enums\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\LanguageDatabaseService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadUsersForKlaviyoController extends Controller
{
    const SIZE_CHUNK = 500;
    
    protected $filename;
    
    protected $fileHandle;

    protected $klaviyoProfileKeys = [];

    protected $language;

    protected $languageDatabaseService;

    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        if (User::count() > 0) {
            $this->klaviyoProfileKeys = array_keys(User::first()->klaviyoProfile());
        } else {
            throw new Exception('No users to export.');
        }

        $this->languageDatabaseService = $languageDatabaseService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->language = $request->has('lang') ? UserLanguage::coerce($request->lang) : UserLanguage::English();

        abort_unless($this->language, Response::HTTP_BAD_REQUEST, 'Invalid language requested.');

        $this->languageDatabaseService->setLanguage($this->language);

        $this->filename = 'users_export_for_klaviyo_' . $this->language->value . '.csv';

        return new StreamedResponse(function () {
            $this->openFile();
            
            $this->addBomUtf8();
            
            $this->addHeadings();
            
            $this->addContent();
            
            $this->closeFile();
        }, Response::HTTP_OK, $this->headers());
    }
    
    protected function openFile()
    {
        $this->fileHandle = fopen('php://output', 'w');
    }
    
    protected function addBomUTf8()
    {
        fwrite($this->fileHandle, $bom = (chr(0xEF).chr(0xBB).chr(0xBF)));
    }
    
    protected function addHeadings()
    {
        $headers = array_merge([
            'email',
        ], $this->klaviyoProfileKeys);

        $this->addRow($headers);
    }
    
    protected function addContent()
    {
        User::chunk(self::SIZE_CHUNK, function ($users) {
            foreach ($users as $user) {
                $this->addUserRow($user);
            }
        });
    }
    
    protected function addUserRow(User $user)
    {
        $row = array_merge([
            $user->email,
        ], $user->klaviyoProfile());

        $this->addRow($row);
    }
    
    protected function addRow(array $data)
    {
        fputcsv($this->fileHandle, $data, ';');
    }
    
    protected function closeFile()
    {
        fclose($this->fileHandle);
    }
    
    protected function headers()
    {
        return [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
        ];
    }
}
