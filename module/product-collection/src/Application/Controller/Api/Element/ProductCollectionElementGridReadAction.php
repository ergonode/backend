<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\Element;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionElementGridQueryInterface;
use Ergonode\ProductCollection\Infrastructure\Grid\ProductCollectionElementGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Factory\DbalDataSetFactory;

/**
 * @Route(
 *     path = "/collections/{productCollection}/elements",
 *     methods={"GET"},
 *     requirements={"productCollection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 *     )
 */
class ProductCollectionElementGridReadAction
{
    private ProductCollectionElementGridBuilder $gridBuilder;

    private ProductCollectionElementGridQueryInterface $elementQuery;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        ProductCollectionElementGridBuilder $gridBuilder,
        ProductCollectionElementGridQueryInterface $elementQuery,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->elementQuery = $elementQuery;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_GET_ELEMENT_GRID")
     *
     * @SWG\Tag(name="Product Collection")
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
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
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
     *     description="Returns import",
     * )
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        Language $language,
        RequestGridConfiguration $configuration,
        ProductCollection $productCollection
    ): Response {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->elementQuery->getGridQuery($productCollection->getId(), $language));
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
