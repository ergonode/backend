<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\AttributeGroup;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Infrastructure\Grid\AttributeGroupGridBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/attributes/groups", methods={"GET"})
 */
class AttributeGroupGridReadAction
{
    private AttributeGroupGridBuilder $gridBuilder;

    private AttributeGroupQueryInterface $query;

    private GridRenderer $gridRenderer;

    public function __construct(
        GridRenderer $gridRenderer,
        AttributeGroupGridBuilder $gridBuilder,
        AttributeGroupQueryInterface $query
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("ATTRIBUTE_GET_GROUP_GRID")
     *
     * @SWG\Tag(name="Attribute")
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
     *     description="Returns attribute group collection"
     * )
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->query->getDataSet($language);
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
