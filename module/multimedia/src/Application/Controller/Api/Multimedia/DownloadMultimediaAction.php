<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Api\Application\Response\FileContentResponse;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Storage\MultimediaStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_multimedia_download",
 *     path="/multimedia/{multimedia}/download",
 *     methods={"GET"},
 *     requirements={"multimedia" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class DownloadMultimediaAction
{
    /**
     * @var MultimediaStorageInterface
     */
    private MultimediaStorageInterface $storage;

    /**
     * @param MultimediaStorageInterface $storage
     */
    public function __construct(MultimediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
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
     *
     * @param Multimedia $multimedia
     *
     * @ParamConverter(class="Ergonode\Multimedia\Domain\Entity\Multimedia")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Multimedia $multimedia): Response
    {
        $content = $this->storage->read($multimedia->getFileName());

        return new FileContentResponse($content, $multimedia);
    }
}
