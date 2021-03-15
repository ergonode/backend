<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_type_read",
 *     path="/collections/type/{productCollectionType}",
 *     methods={"GET"},
 *     requirements={"productCollectionType"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionTypeReadAction
{
    /**
     * @IsGranted("PRODUCT_COLLECTION_GET_TYPE")
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
     *     name="type",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Collection Id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns type",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(ProductCollectionType $productCollectionType): Response
    {
        return new SuccessResponse($productCollectionType);
    }
}
