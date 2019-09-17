<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Exception\ConditionStrategyNotFoundException;
use Ergonode\Condition\Domain\Provider\ConditionConfigurationProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ConditionController extends AbstractController
{
    /**
     * @var ConditionConfigurationProvider
     */
    private $provider;

    /**
     * @param ConditionConfigurationProvider $provider
     */
    public function __construct(ConditionConfigurationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @Route("/conditions/{condition}", methods={"GET"})
     *
     * @IsGranted("CONDITION_READ")
     *
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
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
     *
     * @param Language $language
     * @param string   $condition
     *
     * @return Response
     */
    public function getCondition(Language $language, string $condition): Response
    {
        try {
            $configuration = $this->provider->getConfiguration($language, $condition);
        } catch (ConditionStrategyNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return new SuccessResponse($configuration);
    }
}
