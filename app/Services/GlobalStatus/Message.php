<?php

namespace App\Services\GlobalStatus;

use App\Enums\StatusType;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use App\Services\GlobalStatus\Contracts\MessageContract;

abstract class Message implements Arrayable, MessageContract
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->type()->value,
            'title' => $this->title(),
            'message' => $this->message(),
            'button_text' => $this->buttonText(),
            'button_url' => $this->buttonUrl(),
            'updated_at' => $this->updatedAt()->toDateTimeString(),
        ];
    }

    abstract protected function type(): StatusType;

    abstract protected function title(): string;

    abstract protected function message(): string;

    abstract protected function buttonText(): string;

    abstract protected function buttonUrl(): string;

    abstract protected function updatedAt(): Carbon;
}
