<?php

namespace Wqa\DeepLinkTool;

use Laravel\Nova\ResourceTool;

class DeepLinkTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Links';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'deep-link-tool';
    }

    public function forResource(string $type, string $uuid)
    {
        return $this->withMeta([
            'resourceType' => $type,
            'resourceUuid' => $uuid,
        ]);
    }
}
