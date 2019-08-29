<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\AttributePrice\Domain\Query\CurrencyQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractController
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
     *     description="Language code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection of currencies",
     * )
     *
     * @return Response
     */
    public function getCurrencies(): Response
    {
        $languages = $this->currencyQuery->getDictionary();

        return new SuccessResponse($languages);
    }
}
