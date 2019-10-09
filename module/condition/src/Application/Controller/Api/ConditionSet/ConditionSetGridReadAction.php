<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Ergonode\Condition\Infrastructure\Grid\ConditionSetGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conditionsets", methods={"GET"})
 */
class ConditionSetGridReadAction
{
    /**
     * @var ConditionSetGrid
     */
    private $conditionSetGrid;

    /**
     * @var ConditionSetQueryInterface
     */
    private $conditionSetQuery;

    /**
     * @var GridRenderer
     */
    private $gridRenderer;

    /**
     * @param GridRenderer               $gridRenderer
     * @param ConditionSetGrid           $conditionSetGrid
     * @param ConditionSetQueryInterface $conditionSetQuery
     */
    public function __construct(
        GridRenderer $gridRenderer,
        ConditionSetGrid $conditionSetGrid,
        ConditionSetQueryInterface $conditionSetQuery
    ) {
        $this->conditionSetGrid = $conditionSetGrid;
        $this->conditionSetQuery = $conditionSetQuery;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("CONDITION_READ")
     *
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id", "code", "name", "description"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC", "DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN", "DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns condition set collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $data = $this->gridRenderer->render(
            $this->conditionSetGrid,
            $configuration,
            $this->conditionSetQuery->getDataSet($language),
            $language
        );

        return new SuccessResponse($data);
    }
}
