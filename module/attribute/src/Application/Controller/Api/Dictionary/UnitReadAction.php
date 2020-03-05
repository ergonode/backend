<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\UnitQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/units", methods={"GET"})
 */
class UnitReadAction
{
    /**
     * @var UnitQueryInterface
     */
    private UnitQueryInterface $unitQuery;

    /**
     * @param UnitQueryInterface $unitQuery
     */
    public function __construct(UnitQueryInterface $unitQuery)
    {
        $this->unitQuery = $unitQuery;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection of units",
     * )
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $languages = $this->unitQuery->getDictionary();

        return new SuccessResponse($languages);
    }
}
