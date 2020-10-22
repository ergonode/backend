<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api\Relations;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;
use Ergonode\Product\Infrastructure\Grid\AssociatedProductAvailableChildrenGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route(
 *      name="ergonode_product_available",
 *     path="products/{product}/children-and-available-products",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AssociatedProductAvailableChildrenAction
{
    private ProductChildrenQueryInterface $query;

    private GridRenderer $gridRenderer;

    private AssociatedProductAvailableChildrenGrid $grid;

    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(
        ProductChildrenQueryInterface $query,
        GridRenderer $gridRenderer,
        AssociatedProductAvailableChildrenGrid $grid,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
        $this->grid = $grid;
        $this->attributeRepository = $attributeRepository;
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
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractAssociatedProduct")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        AbstractAssociatedProduct $product,
        Language $language,
        RequestGridConfiguration $configuration
    ): Response {
        $bindingAttributes = [];
        if ($product instanceof VariableProduct) {
            $bindings = $product->getBindings();
            foreach ($bindings as $binding) {
                $bindingAttributes[] = $this->attributeRepository->load($binding);
            }
            $this->grid->addBindingAttributes($bindingAttributes);
        }
        $this->grid->addAssociatedProduct($product);
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getChildrenAndAvailableProductsDataSet($product, $language, $bindingAttributes),
            $language
        );

        return new SuccessResponse($data);
    }
}
