<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Multimedia\Infrastructure\Provider\ImageFormatProvider;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image_format", methods={"GET"})
 */
class ImageFormatReadAction
{
    /**
     * @var ImageFormatProvider
     */
    private ImageFormatProvider $imageFormatProvider;

    /**
     * @param ImageFormatProvider $imageFormatProvider
     */
    public function __construct(ImageFormatProvider $imageFormatProvider)
    {
        $this->imageFormatProvider = $imageFormatProvider;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection of available image formats",
     * )
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return new SuccessResponse($this->imageFormatProvider->dictionary());
    }
}
