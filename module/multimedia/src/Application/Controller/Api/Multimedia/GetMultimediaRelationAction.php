<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
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
    /**
     * @var MultimediaRelationProvider
     */
    private MultimediaRelationProvider $provider;

    /**
     * @param MultimediaRelationProvider $provider
     */
    public function __construct(MultimediaRelationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @IsGranted("MULTIMEDIA_READ")
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
     *     default="en",
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
     *
     * @param Language   $language
     * @param Multimedia $multimedia
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Multimedia\Domain\Entity\Multimedia")
     *
     */
    public function __invoke(Language $language, Multimedia $multimedia): Response
    {
        return new SuccessResponse($this->provider->provide($multimedia->getId(), $language));
    }
}
