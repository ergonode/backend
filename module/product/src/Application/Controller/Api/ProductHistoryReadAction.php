<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Query\HistoryQueryInterface;
use Ergonode\Product\Infrastructure\Grid\ProductHistoryGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_history_read",
 *     path="/products/{product}/history",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 *     )
 */
class ProductHistoryReadAction
{
    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;
    /**
     * @var HistoryQueryInterface
     */
    private HistoryQueryInterface $query;
    /**
     * @var ProductHistoryGrid
     */
    private ProductHistoryGrid $grid;

    /**
     * @param GridRenderer          $gridRenderer
     * @param HistoryQueryInterface $query
     * @param ProductHistoryGrid    $grid
     */
    public function __construct(
        GridRenderer $gridRenderer,
        HistoryQueryInterface $query,
        ProductHistoryGrid $grid
    ) {
        $this->gridRenderer = $gridRenderer;
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product ID",
     * )
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
     *     enum={"recorded_at", "event"},
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
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns product history collection"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @param Language                 $language
     * @param AbstractProduct          $product
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(
        Language $language,
        AbstractProduct $product,
        RequestGridConfiguration $configuration
    ): Response {
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet($product->getId()),
            $language
        );

        return new SuccessResponse($data);
    }
}
