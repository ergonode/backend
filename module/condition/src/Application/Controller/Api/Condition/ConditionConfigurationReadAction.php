<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\Condition;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Exception\ConditionStrategyNotFoundException;
use Ergonode\Condition\Infrastructure\Provider\ConditionConfigurationProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conditions/{condition}", methods={"GET"})
 */
class ConditionConfigurationReadAction
{
    private ConditionConfigurationProvider $provider;

    public function __construct(ConditionConfigurationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="condition",
     *     in="path",
     *     type="string",
     *     description="Condition ID"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns condition"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language, string $condition): Response
    {
        try {
            $configuration = $this->provider->getConfiguration($language, $condition);
        } catch (ConditionStrategyNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return new SuccessResponse($configuration);
    }
}
