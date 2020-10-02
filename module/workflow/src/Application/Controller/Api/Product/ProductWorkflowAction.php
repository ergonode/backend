<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Product;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;

/**
 * @Route(
 *     name="ergonode_product_workflow_read",
 *     path="products/{product}/workflow/{productLanguage}",
 *     methods={"Get"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductWorkflowAction
{
    /**
     * @var ProductWorkflowQuery $query
     */
    private ProductWorkflowQuery $query;

    /**
     * @param ProductWorkflowQuery $query
     */
    public function __construct(ProductWorkflowQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("PRODUCT_READ")
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
     * @SWG\Parameter(
     *     name="productLanguage",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Product language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns product workflow information",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param AbstractProduct $product
     * @param Language        $language
     * @param string          $productLanguage
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @return Response
     *
     * @throws \ReflectionException
     */
    public function __invoke(AbstractProduct $product, Language $language, string $productLanguage): Response
    {
        $result = $this->query->getQuery($product, $language, new Language($productLanguage));

        return new SuccessResponse($result);
    }
}
