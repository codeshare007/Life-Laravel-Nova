<?php

namespace Wqa\CardPreviewTool;

use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;
use Laravel\Nova\ResourceToolElement;

class CardPreviewTool extends ResourceTool
{
    public function __construct()
    {
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
        return 'Card Preview';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'card-preview-tool';
    }
}
