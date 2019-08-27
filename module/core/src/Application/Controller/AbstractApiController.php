<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Renderer\ColumnRenderer;
use Ergonode\Grid\Renderer\FilterRenderer;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\Renderer\InfoRender;
use Ergonode\Grid\Renderer\RowRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

/**
 */
abstract class AbstractApiController extends AbstractFOSRestController
{
    /**
     * @param mixed $data
     * @param array $groups
     * @param int   $code
     *
     * @return Response
     */
    public function createRestResponse($data, array $groups = [], int $code = Response::HTTP_OK): Response
    {
        $view = $this->view($data, $code);

        if (!empty($groups)) {
            $view->setContext((new Context())->setGroups($groups));
        }

        return $this->handleView($view);
    }

    /**
     * @param AbstractGrid             $grid
     * @param RequestGridConfiguration $configuration
     * @param DataSetInterface         $dataSet
     * @param Language                 $language
     *
     * @return array
     */
    public function renderGrid(AbstractGrid $grid, RequestGridConfiguration $configuration, DataSetInterface $dataSet, Language $language): array
    {
        $renderer = new GridRenderer(new ColumnRenderer(new FilterRenderer()), new RowRenderer(), new InfoRender());

        return $renderer->render($grid, $configuration, $dataSet, $language);
    }
}
