<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Application\Controller\Api;

use Ergonode\AttributeImage\Infrastructure\Provider\ImageFormatProvider;
use Ergonode\Core\Application\Response\SuccessResponse;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractController
{
    /**
     * @var ImageFormatProvider
     */
    private $imageFormatProvider;

    /**
     * @param ImageFormatProvider $imageFormatProvider
     */
    public function __construct(ImageFormatProvider $imageFormatProvider)
    {
        $this->imageFormatProvider = $imageFormatProvider;
    }

    /**
     * @Route("/image_format", methods={"GET"})
     *
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
    public function getImageFormat(): Response
    {
        return new SuccessResponse($this->imageFormatProvider->dictionary());
    }
}
