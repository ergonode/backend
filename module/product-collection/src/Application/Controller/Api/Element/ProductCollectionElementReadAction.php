<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\Element;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_element_read",
 *     path="/collections/{productCollection}/elements/{product}",
 *     methods={"GET"},
 *     requirements={
 *     "productCollection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *      "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     },
 * )
 */
class ProductCollectionElementReadAction
{
    /**
     * @IsGranted("PRODUCT_COLLECTION_GET_ELEMENT")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="productCollection",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Collection Id",
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
     *     description="Returns Product collection element",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(ProductCollection $productCollection, AbstractProduct $product): Response
    {
        if ($productCollection->hasElement($product->getId())) {
            return new SuccessResponse($productCollection->getElement($product->getId()));
        }

        return new SuccessResponse($productCollection);
    }
}
