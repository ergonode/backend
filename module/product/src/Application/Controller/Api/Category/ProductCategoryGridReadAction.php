<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api\Category;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Query\ProductCategoryQueryInterface;
use Ergonode\Product\Infrastructure\Grid\ProductCategoryGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="products/{product}/category",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCategoryGridReadAction
{
    /**
     * @var ProductCategoryQueryInterface
     */
    private ProductCategoryQueryInterface $productCategoryQuery;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    private ProductCategoryGrid $productCategoryGrid;

    /**
     * @param ProductCategoryQueryInterface $productCategoryQuery
     * @param GridRenderer                  $gridRenderer
     * @param ProductCategoryGrid           $productCategoryGrid
     */
    public function __construct(
        ProductCategoryQueryInterface $productCategoryQuery,
        GridRenderer $gridRenderer,
        ProductCategoryGrid $productCategoryGrid
    ) {
        $this->productCategoryQuery = $productCategoryQuery;
        $this->gridRenderer = $gridRenderer;
        $this->productCategoryGrid = $productCategoryGrid;
    }


    /**
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
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
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
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
     *     description="Returns categories from product",
     * )
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
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
        $dataSet = $this->productCategoryQuery->getDataSetByProduct($language, $product->getId());

        $data = $this->gridRenderer->render(
            $this->productCategoryGrid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
