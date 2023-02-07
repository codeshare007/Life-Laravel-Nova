<?php

namespace Wqa\UgcTool;

use Laravel\Nova\ResourceTool;
use Laravel\Nova\ResourceToolElement;
use Laravel\Nova\Panel;

class UgcTool extends ResourceTool
{
    protected $args;

    public function __construct($arguments)
    {
        $this->args = $arguments;
        $resourceToolElement = (new ResourceToolElement($this->component()));
        $resourceToolElement->onlyOnDetail = false;
        $resourceToolElement->showOnUpdate = true;
        $resourceToolElement->showOnDetail = true;

        Panel::__construct($this->name(), [$resourceToolElement]);

        $this->element = $this->data[0];
    }

    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return $this->args['heading'] ?? 'Ugc Tool';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'ugc-tool';
    }
}
