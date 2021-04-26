<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Product\Infrastructure\Grid\ProductRelationGridBuilder;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Query\ProductRelationAttributeGridQueryInterface;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

/**
 * @Route(
 *      name="ergonode_product_available",
 *     path="products/{product}/related/{attribute}",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
                     "attribute"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductRelatedProductByAttributeAction
{
    private ProductRelationAttributeGridQueryInterface $query;

    private GridRenderer $gridRenderer;

    private DbalDataSetFactory $factory;

    private ProductRelationGridBuilder $gridBuilder;

    public function __construct(
        ProductRelationAttributeGridQueryInterface $query,
        GridRenderer $gridRenderer,
        DbalDataSetFactory $factory,
        ProductRelationGridBuilder $gridBuilder
    ) {
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
        $this->factory = $factory;
        $this->gridBuilder = $gridBuilder;
    }

    /**
     * @IsGranted("PRODUCT_GET_RELATIONS_AVAILABLE")
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
     *     enum={"sku","template", "default_label", "attached"},
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
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        AbstractProduct $product,
        ProductRelationAttribute $attribute,
        Language $language,
        RequestGridConfiguration $configuration
    ): Response {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->query->getGridQuery($product->getId(), $attribute->getId(), $language));
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
