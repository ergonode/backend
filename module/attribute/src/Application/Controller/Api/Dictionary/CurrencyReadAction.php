<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;

/**
 * @Route("/dictionary/currencies", methods={"GET"})
 */
class CurrencyReadAction
{
    /**
     * @var CurrencyQueryInterface
     */
    private CurrencyQueryInterface $currencyQuery;

    /**
     * @param CurrencyQueryInterface $currencyQuery
     */
    public function __construct(CurrencyQueryInterface $currencyQuery)
    {
        $this->currencyQuery = $currencyQuery;
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
     *     description="Returns collection of currencies",
     * )
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $languages = $this->currencyQuery->getDictionary();

        return new SuccessResponse($languages);
    }
}
