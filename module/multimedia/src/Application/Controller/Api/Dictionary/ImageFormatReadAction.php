<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/image_format", methods={"GET"})
 */
class ImageFormatReadAction
{
    private MultimediaExtensionProvider $provider;

    public function __construct(MultimediaExtensionProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Dictionary")
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
     *     description="Returns collection of available image formats",
     * )
     */
    public function __invoke(): Response
    {
        return new SuccessResponse($this->provider->dictionary());
    }
}
