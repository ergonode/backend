<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Provider\ConditionConfigurationProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

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
     * @Route("/conditon/{condition}", methods={"GET"})
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="segment",
     *     in="path",
     *     type="string",
     *     description="Segment id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns segment",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     * @param string   $condition
     *
     * @return Response
     */
    public function getCondition(Language $language, string $condition): Response
    {
        $configuration = $this->provider->getConfiguration($language, $condition);

        return new SuccessResponse($configuration);
    }
}
