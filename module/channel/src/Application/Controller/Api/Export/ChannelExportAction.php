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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route(
 *     name="ergonode_channel_export",
 *     path="/channels/{channel}/exports/{export}",
 *     methods={"GET"},
 *     requirements={
 *        "channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "export" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ChannelExportAction
{
    private ExportQueryInterface  $query;

    private FilesystemInterface $exportStorage;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ExportQueryInterface $query,
        FilesystemInterface $exportStorage,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->query = $query;
        $this->exportStorage = $exportStorage;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @IsGranted("CHANNEL_GET_EXPORT")
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
     *     description="Returns export information",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\Export")
     */
    public function __invoke(Language $language, AbstractChannel $channel, Export $export): Response
    {
        $file = sprintf('%s.zip', $export->getId()->getValue());

        $result = $this->query->getInformation($export->getId());

        if ($this->exportStorage->has($file)) {
            $result['_links']['attachment'] = [
                'href' => $this->urlGenerator->generate(
                    'ergonode_channel_export_download',
                    [
                        'language' => $language->getCode(),
                        'channel' => $channel->getId()->getValue(),
                        'export' => $export->getId()->getValue(),
                    ],
                    UrlGeneratorInterface::NETWORK_PATH
                ),
                'method' => Request::METHOD_GET,
            ];
        }

        return new SuccessResponse($result);
    }
}
