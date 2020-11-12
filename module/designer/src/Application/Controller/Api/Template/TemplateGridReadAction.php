<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Controller\Api\Template;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Grid\TemplateGrid;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/templates", methods={"GET"})
 */
class TemplateGridReadAction
{
    private TemplateQueryInterface $designerTemplateQuery;

    private TemplateGrid $templateGrid;

    private GridRenderer $gridRenderer;

    public function __construct(
        GridRenderer $gridRenderer,
        TemplateQueryInterface $designerTemplateQuery,
        TemplateGrid $templateGrid
    ) {
        $this->designerTemplateQuery = $designerTemplateQuery;
        $this->templateGrid = $templateGrid;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines"
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line"
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id", "label","code", "hint"},
     *     description="Order field"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order"
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
    * @SWG\Parameter(
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns templates"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $dataSet = $this->designerTemplateQuery->getDataSet();

        $data = $this->gridRenderer->render(
            $this->templateGrid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
