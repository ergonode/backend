<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Core\Application\HttpFoundation\FileContentResponse;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Service\Thumbnail\ThumbnailGenerator;
use League\Flysystem\FilesystemInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_multimedia_download_thumbnail",
 *     path="/multimedia/{multimedia}/download/{thumbnail}",
 *     methods={"GET"},
 *     requirements={
 *        "multimedia" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class DownloadThumbnailMultimediaAction
{
    private ThumbnailGenerator $generator;

    private FilesystemInterface $thumbnailStorage;

    public function __construct(ThumbnailGenerator $generator, FilesystemInterface $thumbnailStorage)
    {
        $this->generator = $generator;
        $this->thumbnailStorage = $thumbnailStorage;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL")
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="path",
     *     type="string",
     *     description="Multimedia id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns multimedia file",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(Multimedia $multimedia, string $thumbnail): Response
    {
        try {
            $filename = sprintf('%s/%s.png', $thumbnail, $multimedia->getId()->getValue());
            if (!$this->thumbnailStorage->has($filename)) {
                $this->generator->generate($multimedia, $thumbnail);
            }

            return new FileContentResponse($filename, $this->thumbnailStorage);
        } catch (\Exception $exception) {
            throw new NotFoundHttpException(null, $exception);
        }
    }
}
