<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_read",
 *     path="products/{product}",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductReadAction
{
    private GetProductQueryInterface $getProductQuery;

    public function __construct(GetProductQueryInterface $getProductQuery)
    {
        $this->getProductQuery = $getProductQuery;
    }

    /**
     * @IsGranted("PRODUCT_GET")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="product ID",
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
     *     description="Returns product",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(AbstractProduct $product, Language $language): Response
    {
        $result =  $this->getProductQuery->query($product->getId(), $language);

        return new SuccessResponse($result);
    }
}
