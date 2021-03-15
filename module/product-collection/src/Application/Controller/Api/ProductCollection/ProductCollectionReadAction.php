<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_read",
 *     path="/collections/{productCollection}",
 *     methods={"GET"},
 *     requirements={"productCollection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionReadAction
{
    /**
     * @IsGranted("PRODUCT_COLLECTION_GET")
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(ProductCollection $productCollection): Response
    {
        return new SuccessResponse($productCollection);
    }
}
