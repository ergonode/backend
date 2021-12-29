<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api\Export;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Channel\Domain\Query\ExportErrorGridQueryInterface;
use Ergonode\Grid\GridBuilderInterface;

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
    private GridBuilderInterface $gridBuilder;

    private ExportErrorGridQueryInterface $query;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        GridBuilderInterface $gridBuilder,
        ExportErrorGridQueryInterface $query,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->query = $query;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_CHANNEL_GET_EXPORT_ERROR_GRID")
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
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel", name="channel")
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\Export", name="export")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration", name="configuration")
     */
    public function __invoke(
        Language $language,
        AbstractChannel $channel,
        Export $export,
        RequestGridConfiguration $configuration
    ): array {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->query->getGridQuery($export->getId(), $language));

        return $this->gridRenderer->render($grid, $configuration, $dataSet);
    }
}
