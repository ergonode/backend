<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeDate\Application\Controller\Api;

use Ergonode\AttributeDate\Infrastructure\Provider\DateFormatProvider;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractApiController
{
    /**
     * @var DateFormatProvider
     */
    private $dateFormatProvider;

    /**
     * @param DateFormatProvider $dateFormatProvider
     */
    public function __construct(DateFormatProvider $dateFormatProvider)
    {
        $this->dateFormatProvider = $dateFormatProvider;
    }

    /**
     * @Route("/date_format", methods={"GET"})
     *
     * @IsGranted("ATTRIBUTE_READ")
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
     *     description="Returns collection of available date formats",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @return Response
     */
    public function getDateFormat(): Response
    {
        return $this->createRestResponse($this->dateFormatProvider->dictionary());
    }
}
