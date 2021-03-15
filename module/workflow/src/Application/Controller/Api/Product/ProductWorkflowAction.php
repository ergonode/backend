<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Product;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;
use Ergonode\Workflow\Domain\Provider\ProductStatusProvider;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

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
    private ProductWorkflowQuery $query;

    private ProductStatusProvider $statusProvider;

    private WorkflowProvider $workflowProvider;

    public function __construct(
        ProductWorkflowQuery $query,
        ProductStatusProvider $statusProvider,
        WorkflowProvider $workflowProvider
    ) {
        $this->query = $query;
        $this->statusProvider = $statusProvider;
        $this->workflowProvider = $workflowProvider;
    }

    /**
     * @IsGranted("WORKFLOW_GET_PRODUCT")
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
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Language $language, Language $productLanguage): Response
    {
        $workflow = $this->workflowProvider->provide();
        $product = $this->statusProvider->getProduct($product, $workflow, $productLanguage);
        $result = $this->query->getQuery($product, $workflow, $language, $productLanguage);

        return new SuccessResponse($result);
    }
}
