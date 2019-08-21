<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Response;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Renderer\ColumnRenderer;
use Ergonode\Grid\Renderer\FilterRenderer;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\Renderer\InfoRender;
use Ergonode\Grid\Renderer\RowRenderer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class GridResponse extends JsonResponse
{
    /**
     * @param AbstractGrid               $grid
     * @param GridConfigurationInterface $configuration
     * @param DataSetInterface           $dataSet
     * @param Language                   $language
     */
    public function __construct(
        AbstractGrid $grid,
        GridConfigurationInterface $configuration,
        DataSetInterface $dataSet,
        Language $language
    ) {
        $renderer = new GridRenderer(new ColumnRenderer(new FilterRenderer()), new RowRenderer(), new InfoRender());
        $data = $renderer->render($grid, $configuration, $dataSet, $language);

        parent::__construct($data, Response::HTTP_OK);
    }
}
