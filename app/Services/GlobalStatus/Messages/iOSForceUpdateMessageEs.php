<?php

namespace App\Services\GlobalStatus\Messages;

class iOSForceUpdateMessageEs extends iOSForceUpdateMessageEn
{
    protected function title(): string
    {
        return 'Actualización disponible';
    }
    
    protected function message(): string
    {
        return 'Woohoo! La última versión de la aplicación Essential Life está disponible.

Vaya a la tienda de aplicaciones de iOS y descargue la última versión.';
    }
    
    protected function buttonText(): string
    {
        return 'ACTUALIZAR';
    }
}
