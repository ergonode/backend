<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Ergonode\ProductCollection\Infrastructure\Grid\ProductCollectionTypeGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collections/type", methods={"GET"})
 */
class ProductCollectionTypeGridReadAction
{
    /**
     * @var ProductCollectionTypeGrid
     */
    private ProductCollectionTypeGrid $productCollectionTypeGrid;

    /**
     * @var ProductCollectionTypeQueryInterface
     */
    private ProductCollectionTypeQueryInterface $collectionTypeQuery;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @param ProductCollectionTypeGrid           $productCollectionTypeGrid
     * @param ProductCollectionTypeQueryInterface $collectionTypeQuery
     * @param GridRenderer                        $gridRenderer
     */
    public function __construct(
        ProductCollectionTypeGrid $productCollectionTypeGrid,
        ProductCollectionTypeQueryInterface $collectionTypeQuery,
        GridRenderer $gridRenderer
    ) {
        $this->productCollectionTypeGrid = $productCollectionTypeGrid;
        $this->collectionTypeQuery = $collectionTypeQuery;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     *
     * @IsGranted("PRODUCT_COLLECTION_READ")
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
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $dataSet = $this->collectionTypeQuery->getDataSet($language);

        $data = $this->gridRenderer->render(
            $this->productCollectionTypeGrid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
