<?php

namespace Wqa\UserDebug;

use Laravel\Nova\ResourceTool;

class UserDebug extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Debug';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'UserDebug';
    }
}
