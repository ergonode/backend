<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\AttributeGridQueryInterface;
use Ergonode\Attribute\Infrastructure\Grid\AttributeGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/attributes", methods={"GET"})
 */
class AttributeGridReadAction
{
    /**
     * @var AttributeGrid
     */
    private AttributeGrid $attributeGrid;

    /**
     * @var AttributeGridQueryInterface
     */
    private AttributeGridQueryInterface $attributeGridQuery;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @param GridRenderer                $gridRenderer
     * @param AttributeGrid               $attributeGrid
     * @param AttributeGridQueryInterface $attributeGridQuery
     */
    public function __construct(
        GridRenderer $gridRenderer,
        AttributeGrid $attributeGrid,
        AttributeGridQueryInterface $attributeGridQuery
    ) {
        $this->attributeGrid = $attributeGrid;
        $this->attributeGridQuery = $attributeGridQuery;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("ATTRIBUTE_READ")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
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
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
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
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute collection",
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
        $dataSet = $this->attributeGridQuery->getDataSet($language);

        $data = $this->gridRenderer->render(
            $this->attributeGrid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
