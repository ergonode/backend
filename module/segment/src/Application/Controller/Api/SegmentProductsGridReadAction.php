<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\Segment\Infrastructure\Grid\SegmentProductsGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path = "/segments/{segment}/products",
 *     methods={"GET"},
 *     requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 *     )
 */
class SegmentProductsGridReadAction
{
    private SegmentProductsGrid $segmentProductsGrid;

    private SegmentProductsQueryInterface $segmentProductsQuery;

    private GridRenderer $gridRenderer;

    public function __construct(
        SegmentProductsGrid $segmentProductsGrid,
        SegmentProductsQueryInterface $elementQuery,
        GridRenderer $gridRenderer
    ) {
        $this->segmentProductsGrid = $segmentProductsGrid;
        $this->segmentProductsQuery = $elementQuery;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("SEGMENT_READ")
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
        $dataSet = $this->segmentProductsQuery->getDataSet($segment->getId());

        $data = $this->gridRenderer->render(
            $this->segmentProductsGrid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
