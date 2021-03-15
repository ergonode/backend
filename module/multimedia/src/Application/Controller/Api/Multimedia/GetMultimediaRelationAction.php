<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationProvider;

/**
 * @Route(
 *     name="ergonode_multimedia_relation",
 *     path="/{language}/multimedia/{multimedia}/relation",
 *     methods={"GET"},
 *     requirements={"multimedia" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}*
 * )
 */
class GetMultimediaRelationAction
{
    private MultimediaRelationProvider $provider;

    public function __construct(MultimediaRelationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @IsGranted("MULTIMEDIA_GET_RELATION")
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Multimedia id",
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
     *     description="Returns multimedia relations",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(Language $language, Multimedia $multimedia): Response
    {
        return new SuccessResponse($this->provider->provide($multimedia->getId(), $language));
    }
}
