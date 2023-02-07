<?php

namespace Wqa\GlobalStatus\Http\Controllers;

use App\Enums\StatusType;
use Illuminate\Http\Request;
use Unisharp\Setting\Setting;
use App\Http\Controllers\Controller;
use Wqa\GlobalStatus\Http\Requests\UpdateRequest;

class StatusController extends Controller
{
    /** @var Setting */
    protected $settings;

    public function __construct(Setting $settings)
    {
        $this->settings = $settings;
    }

    public function index(Request $request)
    {
        return response()->json([
            'ios_status' => [
                'type' => $this->settings->get('ios_status_type', StatusType::Ok),
                'title' => $this->settings->get('ios_status_title', ''),
                'message' => $this->settings->get('ios_status_message', ''),
                'button_text' => $this->settings->get('ios_status_button_text', ''),
                'button_url' => $this->settings->get('ios_status_button_url', ''),
                'updated_at' => $this->settings->get('ios_status_updated_at', 'Never'),
            ],
            'android_status' => [
                'type' => $this->settings->get('android_status_type', StatusType::Ok),
                'title' => $this->settings->get('android_status_title', ''),
                'message' => $this->settings->get('android_status_message', ''),
                'button_text' => $this->settings->get('android_status_button_text', ''),
                'button_url' => $this->settings->get('android_status_button_url', ''),
                'updated_at' => $this->settings->get('android_status_updated_at', 'Never'),
            ],
            'status_types' => StatusType::toSelectArray(),
        ]);
    }

    public function update(UpdateRequest $request)
    {
        $this->settings->set($request->platform . '_status_type', $request->type);
        $this->settings->set($request->platform . '_status_title', $request->title);
        $this->settings->set($request->platform . '_status_message', $request->message);
        $this->settings->set($request->platform . '_status_button_text', $request->button_text);
        $this->settings->set($request->platform . '_status_button_url', $request->button_url);
        $this->settings->set($request->platform . '_status_updated_at', now()->toDateTimeString());
    }
}
