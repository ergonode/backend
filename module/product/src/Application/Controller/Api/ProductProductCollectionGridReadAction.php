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
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Infrastructure\Grid\ProductProductCollectionGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="/products/{product}/collections",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductProductCollectionGridReadAction
{
    /**
     * @var ProductProductCollectionGrid
     */
    private ProductProductCollectionGrid $grid;

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $collectionQuery;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @param ProductProductCollectionGrid $grid
     * @param ProductQueryInterface        $productQuery
     * @param GridRenderer                 $gridRenderer
     */
    public function __construct(
        ProductProductCollectionGrid $grid,
        ProductQueryInterface $productQuery,
        GridRenderer $gridRenderer
    ) {
        $this->grid = $grid;
        $this->collectionQuery = $productQuery;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="product ID",
     *     )
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
     *     enum={"sku","index","template"},
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
     *     name="columns",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Columns"
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
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     * @param AbstractProduct          $product
     *
     * @return Response
     */
    public function __invoke(
        Language $language,
        RequestGridConfiguration $configuration,
        AbstractProduct $product
    ): Response {
        $dataSet = $this->collectionQuery->getDataSetByProduct($language, $product->getId());

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
