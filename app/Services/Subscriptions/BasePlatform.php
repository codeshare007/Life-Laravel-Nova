<?php

namespace App\Services\Subscriptions;

use App\Services\Subscriptions\Contracts\PlatformContract;

abstract class BasePlatform implements PlatformContract
{
    const BYPASS_TOKEN = '?=w]{>ykD!wf(h+DQhO~2Ns1Y#q9o>[ZIY)v/1wgx.eN$s4g2<fCpjwa_Mz2TYz';
}