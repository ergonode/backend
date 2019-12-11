<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api\Condition;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Infrastructure\Provider\ConditionProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conditions", methods={"GET"})
 */
class ConditionAction
{
    /**
     * @var ConditionProvider
     */
    private $provider;

    /**
     * @param ConditionProvider $provider
     */
    public function __construct(ConditionProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
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
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     * @throws \ReflectionException
     */
    public function __invoke(Language $language, Request $request): Response
    {
        $group = $request->query->get('group', null);
        $conditions = $this->provider->getConditions($language, $group);

        return new SuccessResponse($conditions);
    }
}
