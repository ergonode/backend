<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Provider\ConditionDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractController
{
    /**
     * @var ConditionDictionaryProvider
     */
    private $provider;

    /**
     * @param ConditionDictionaryProvider $provider
     */
    public function __construct(ConditionDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @Route("conditions", methods={"GET"})
     *
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns dictionary of available conditions"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @param Language $language
     *
     * @return Response
     */
    public function getDictionary(Language $language): Response
    {
        $dictionary = $this->provider->getDictionary($language);

        return new SuccessResponse($dictionary);
    }
}
