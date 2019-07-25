<?php


namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\FilterInterface;

/**
 */
class FilterRenderer
{
    /**
     * @param FilterInterface $filter
     *
     * @return array
     */
    public function render(FilterInterface $filter): array
    {
        $result = [
            'type' => $filter->getType(),
            'value' => $filter->getValue(),
        ];

        return array_merge($result, $filter->render());
    }
}
