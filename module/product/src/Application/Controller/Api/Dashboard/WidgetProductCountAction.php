<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Domain\Query\ProductDashboardQueryInterface;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 * @Route(
 *     name="ergonode_dashboard_widget_product_count",
 *     path="dashboard/widget/product-count",
 *     methods={"GET"}
 * )
 */
class WidgetProductCountAction
{
    /**
     * @var ProductDashboardQueryInterface
     */
    private ProductDashboardQueryInterface  $query;

    /**
     * @param ProductDashboardQueryInterface $query
     */
    public function __construct(ProductDashboardQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("PRODUCT_UPDATE")
     *
     * @SWG\Tag(name="Dashboard")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="widget product count information",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     *
     */
    public function __invoke(Language $language, Request $request): Response
    {
        $result = $this->query->getProductCount($language);

        return new SuccessResponse($result);
    }
}
