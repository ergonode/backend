<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Api\Application\Response\SuccessResponse;

/**
 * @Route(
 *     name="ergonode_multimedia_read",
 *     path="/{language}/multimedia/{multimedia}",
 *     methods={"GET"},
 *     requirements={"multimedia" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class GetMultimediaAction
{
    /**
     * @IsGranted("MULTIMEDIA_GET")
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
     *     description="Returns multimedia",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(Multimedia $multimedia): Response
    {
        return new SuccessResponse($multimedia);
    }
}
