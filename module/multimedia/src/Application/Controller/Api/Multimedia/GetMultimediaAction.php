<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route(
 *     name="ergonode_multimedia_read",
 *     path="/multimedia/{multimedia}",
 *     methods={"GET"},
 * )
 */
class GetMultimediaAction
{
    /**
     * @var MultimediaFileProviderInterface
     */
    private MultimediaFileProviderInterface $fileProvider;

    /**
     * @param MultimediaFileProviderInterface $fileProvider
     */
    public function __construct(MultimediaFileProviderInterface $fileProvider)
    {
        $this->fileProvider = $fileProvider;
    }

    /**
     * @IsGranted("MULTIMEDIA_READ")
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
        $file = $this->fileProvider->getFile($multimedia->getFileName());

        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
