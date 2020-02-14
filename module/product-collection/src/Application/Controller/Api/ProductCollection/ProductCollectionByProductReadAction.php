<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_by_product_read",
 *     path="/collections/product/{product}",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionByProductReadAction
{
    /**
     * @var ProductCollectionQueryInterface
     */
    private ProductCollectionQueryInterface $productCollectionQuery;

    /**
     * ProductCollectionByProductReadAction constructor.
     *
     * @param ProductCollectionQueryInterface $productQuery
     */
    public function __construct(ProductCollectionQueryInterface $productQuery)
    {
        $this->productCollectionQuery = $productQuery;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_READ")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Product Id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @param AbstractProduct $product
     *
     * @return Response
     */
    public function __invoke(AbstractProduct $product): Response
    {
        $productCollections = $this->productCollectionQuery->findProductCollectionIdByProduct($product->getId());

        return new SuccessResponse($productCollections);
    }
}
