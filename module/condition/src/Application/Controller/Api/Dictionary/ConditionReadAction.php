<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Provider\ConditionDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("conditions", methods={"GET"})
 */
class ConditionReadAction
{
    private ConditionDictionaryProvider $provider;

    public function __construct(ConditionDictionaryProvider $provider)
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
     * @SWG\Parameter(
     *     name="group",
     *     in="query",
     *     type="string",
     *     required=false,
     *     enum={"segment", "workflow"},
     *     description="Condition Group",
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
     *
     *
     * @throws \ReflectionException
     */
    public function __invoke(Language $language, Request $request): Response
    {
        $group = $request->query->get('group', null);
        $dictionary = $this->provider->getDictionary($language, $group);

        return new SuccessResponse($dictionary);
    }
}
