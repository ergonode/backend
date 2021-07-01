<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Dictionary;

use Ergonode\Attribute\Infrastructure\Provider\DateFormatProvider;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/date_format", methods={"GET"})
 */
class DateFormatReadAction
{
    private DateFormatProvider $dateFormatProvider;

    public function __construct(DateFormatProvider $dateFormatProvider)
    {
        $this->dateFormatProvider = $dateFormatProvider;
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
     *     description="Returns collection of available date formats",
     * )
     */
    public function __invoke(): array
    {
        return $this->dateFormatProvider->dictionary();
    }
}
