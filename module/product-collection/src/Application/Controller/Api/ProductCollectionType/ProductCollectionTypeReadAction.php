<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_type_read",
 *     path="/collections/type/{type}",
 *     methods={"GET"},
 *     requirements={"type"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionTypeReadAction
{
    /**
     * @IsGranted("PRODUCT_COLLECTION_READ")
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
     *
     * @ParamConverter(class="Ergonode\ProductCollection\Domain\Entity\ProductCollectionType")
     *
     * @param ProductCollectionType $productCollectionType
     *
     * @return Response
     */
    public function __invoke(ProductCollectionType $productCollectionType): Response
    {
        return new SuccessResponse($productCollectionType);
    }
}
