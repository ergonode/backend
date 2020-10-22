<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api\Export;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Channel\Infrastructure\Grid\ExportGrid;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 * @Route(
 *     name="ergonode_channel_export_grid",
 *     path="/channels/{channel}/exports",
 *     methods={"GET"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelExportGridAction
{
    private ExportGrid $grid;

    private ExportQueryInterface $query;

    private GridRenderer $gridRenderer;

    public function __construct(ExportGrid $grid, ExportQueryInterface $query, GridRenderer $gridRenderer)
    {
        $this->grid = $grid;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("CHANNEL_READ")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
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
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns export collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        Language $language,
        AbstractChannel $channel,
        RequestGridConfiguration $configuration
    ): Response {
        $dataSet = $this->query->getDataSet($channel->getId(), $language);

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
