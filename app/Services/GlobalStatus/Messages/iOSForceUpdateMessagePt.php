<?php

namespace App\Services\GlobalStatus\Messages;

class iOSForceUpdateMessagePt extends iOSForceUpdateMessageEn
{
    protected function title(): string
    {
        return 'Atualização disponível';
    }
    
    protected function message(): string
    {
        return 'Woohoo! A versão mais recente do aplicativo Essential Life está disponível.

Acesse a iOS App Store e faça o download da versão mais recente.';
    }
    
    protected function buttonText(): string
    {
        return 'ATUALIZAR';
    }
}
