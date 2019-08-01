<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Application\Controller\Api;

use Ergonode\AttributePrice\Domain\Query\CurrencyQueryInterface;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractApiController
{
    /**
     * @var CurrencyQueryInterface
     */
    private $currencyQuery;

    /**
     * @param CurrencyQueryInterface $currencyQuery
     */
    public function __construct(CurrencyQueryInterface $currencyQuery)
    {
        $this->currencyQuery = $currencyQuery;
    }

    /**
     * @Route("/currencies", methods={"GET"})
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
     *     description="Returns collection of currencies",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @return Response
     */
    public function getCurrencies(): Response
    {
        $languages = $this->currencyQuery->getDictionary();

        return $this->createRestResponse($languages);
    }
}
