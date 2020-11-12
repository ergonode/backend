<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Channel\Infrastructure\Grid\ExportErrorsGrid;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 * @Route(
 *     name="ergonode_channel_export_error_grid",
 *     path="/channels/{channel}/exports/{export}/errors",
 *     methods={"GET"},
 *     requirements={
 *        "channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "export" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ChannelExportErrorGridAction
{
    private ExportErrorsGrid $grid;

    private ExportQueryInterface $query;

    private GridRenderer $gridRenderer;

    public function __construct(ExportErrorsGrid $grid, ExportQueryInterface $query, GridRenderer $gridRenderer)
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
     *     name="channel",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Channel id",
     * )
     * @SWG\Parameter(
     *     name="export",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Export id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns export errors",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     * @ParamConverter(class="Ergonode\Exporter\Domain\Entity\Export")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        Language $language,
        AbstractChannel $channel,
        Export $export,
        RequestGridConfiguration $configuration
    ): Response {
        $dataSet = $this->query->getErrorDataSet($export->getId(), $language);

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
