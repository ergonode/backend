<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\ProductCollection\Infrastructure\Grid\ProductCollectionTypeGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeGridQueryInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;

/**
 * @Route("/collections/type", methods={"GET"})
 */
class ProductCollectionTypeGridReadAction
{
    private ProductCollectionTypeGridBuilder $productCollectionTypeGridBuilder;

    private ProductCollectionTypeGridQueryInterface $collectionTypeQuery;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        ProductCollectionTypeGridBuilder $productCollectionTypeGridBuilder,
        ProductCollectionTypeGridQueryInterface $collectionTypeQuery,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->productCollectionTypeGridBuilder = $productCollectionTypeGridBuilder;
        $this->collectionTypeQuery = $collectionTypeQuery;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_GET_TYPE_GRID")
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
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $grid = $this->productCollectionTypeGridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->collectionTypeQuery->getGridQuery($language));

        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
