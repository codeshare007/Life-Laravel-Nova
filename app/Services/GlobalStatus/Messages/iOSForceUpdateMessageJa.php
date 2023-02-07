<?php

namespace App\Services\GlobalStatus\Messages;

class iOSForceUpdateMessageJa extends iOSForceUpdateMessageEn
{
    protected function title(): string
    {
        return '利用可能なアップデート';
    }

    protected function message(): string
    {
        return 'ウーフー！ EssentialLifeアプリの最新バージョンが利用可能です。

iOS App Storeにアクセスして、最新バージョンをダウンロードしてください。';
    }

    protected function buttonText(): string
    {
        return '更新';
    }
}
