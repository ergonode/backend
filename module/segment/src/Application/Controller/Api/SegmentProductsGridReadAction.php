<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Infrastructure\Grid\SegmentProductsGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Segment\Domain\Query\SegmentProductsGridQueryInterface;

/**
 * @Route(
 *     path = "/segments/{segment}/products",
 *     methods={"GET"},
 *     requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 *     )
 */
class SegmentProductsGridReadAction
{
    private SegmentProductsGridBuilder $segmentProductsGridBuilder;

    private SegmentProductsGridQueryInterface $segmentProductsQuery;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        SegmentProductsGridBuilder $segmentProductsGridBuilder,
        SegmentProductsGridQueryInterface $segmentProductsQuery,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->segmentProductsGridBuilder = $segmentProductsGridBuilder;
        $this->segmentProductsQuery = $segmentProductsQuery;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("SEGMENT_GET_PRODUCT_GRID")
     *
     * @SWG\Tag(name="Segment")
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
        Segment $segment
    ): Response {
        $grid = $this->segmentProductsGridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->segmentProductsQuery->getGridQuery($segment->getId()));
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
