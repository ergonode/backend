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
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\Flysystem\FileNotFoundException;

/**
 * @Route(
 *     name="ergonode_channel_export_download",
 *     path="/channels/{channel}/exports/{export}/download",
 *     methods={"GET"},
 *     requirements={
 *        "channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "export" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ChannelExportDownloadAction
{
    private FilesystemInterface $exportStorage;

    public function __construct(FilesystemInterface $exportStorage)
    {
        $this->exportStorage = $exportStorage;
    }

    /**
     * @IsGranted("CHANNEL_GET_EXPORT_FILE")
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
     *     description="export file download",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\Export")
     *
     *
     *
     * @throws FileNotFoundException
     */
    public function __invoke(Language $language, AbstractChannel $channel, Export $export): Response
    {
        $file = sprintf('%s.zip', $export->getId()->getValue());

        if (!$this->exportStorage->has($file)) {
            throw new NotFoundHttpException();
        }

        $content = $this->exportStorage->read($file);
        $size = $this->exportStorage->getSize($file);

        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="'.$file.'";',
            'Content-length' => $size,
        ];

        return new SuccessResponse($content, $headers);
    }
}
