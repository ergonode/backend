<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\AttributeGroup;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Infrastructure\Grid\AttributeGroupGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/attributes/groups", methods={"GET"})
 */
class AttributeGroupGridReadAction
{
    /**
     * @var AttributeGroupGrid
     */
    private AttributeGroupGrid $grid;

    /**
     * @var AttributeGroupQueryInterface
     */
    private AttributeGroupQueryInterface $query;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @param GridRenderer                 $gridRenderer
     * @param AttributeGroupGrid           $grid
     * @param AttributeGroupQueryInterface $query
     */
    public function __construct(
        GridRenderer $gridRenderer,
        AttributeGroupGrid $grid,
        AttributeGroupQueryInterface $query
    ) {
        $this->grid = $grid;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
    }

    /**
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
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute group collection"
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
            $this->grid,
            $configuration,
            $this->query->getDataSet($language),
            $language
        );

        return new SuccessResponse($data);
    }
}
