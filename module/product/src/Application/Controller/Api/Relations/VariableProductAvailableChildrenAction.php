<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api\Relations;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;
use Ergonode\Product\Infrastructure\Grid\VariableProductAvailableChildrenGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Webmozart\Assert\Assert;

/**
 * @Route(
 *      name="ergonode_product_available",
 *     path="products/{product}/variable/children-and-available-products",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class VariableProductAvailableChildrenAction
{
    /**
     * @var ProductChildrenQueryInterface
     */
    private ProductChildrenQueryInterface $query;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @var VariableProductAvailableChildrenGrid
     */
    private VariableProductAvailableChildrenGrid $grid;

    /**
     * @param ProductChildrenQueryInterface        $query
     * @param GridRenderer                         $gridRenderer
     * @param VariableProductAvailableChildrenGrid $grid
     */
    public function __construct(
        ProductChildrenQueryInterface $query,
        GridRenderer $gridRenderer,
        VariableProductAvailableChildrenGrid $grid
    ) {
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
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
     *     enum={"sku","template", "default_label", "attach_flag"},
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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns products",
     * )
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param AbstractProduct          $product
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(
        AbstractProduct $product,
        Language $language,
        RequestGridConfiguration $configuration
    ): Response {
        Assert::isInstanceOf($product, VariableProduct::class);
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getChildrenAndAvailableProductsDataSet($product->getId(), $product->getBindings(), $language),
            $language
        );

        return new SuccessResponse($data);
    }
}
