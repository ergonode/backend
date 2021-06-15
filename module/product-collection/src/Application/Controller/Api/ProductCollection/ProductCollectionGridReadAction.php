<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\ProductCollection\Infrastructure\Grid\ProductCollectionGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionGridQueryInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;

/**
 * @Route("/collections", methods={"GET"})
 */
class ProductCollectionGridReadAction
{
    private ProductCollectionGridBuilder $productCollectionGridBuilder;

    private ProductCollectionGridQueryInterface $collectionQuery;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        ProductCollectionGridBuilder $productCollectionGridBuilder,
        ProductCollectionGridQueryInterface $collectionQuery,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->productCollectionGridBuilder = $productCollectionGridBuilder;
        $this->collectionQuery = $collectionQuery;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_GET_GRID")
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
    public function __invoke(Language $language, RequestGridConfiguration $configuration): array
    {
        $grid = $this->productCollectionGridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->collectionQuery->getGridQuery($language));

        return $this->gridRenderer->render($grid, $configuration, $dataSet);
    }
}
