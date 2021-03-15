<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api\Scheduler;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Channel\Domain\Provider\SchedulerProvider;
use Ergonode\Api\Application\Response\SuccessResponse;

/**
 * @Route(
 *     name="ergonode_channel_scheduler_change",
 *     path="/channels/{channel}/scheduler",
 *     methods={"GET"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SchedulerGetAction
{
    private SchedulerProvider $provider;

    public function __construct(SchedulerProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @IsGranted("CHANNEL_GET_SCHEDULER")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="channel",
     *     in="path",
     *     type="string",
     *     description="Channel id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add Channel",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/channel")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns Channel",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(AbstractChannel $channel, Request $request): Response
    {
        $scheduler = $this->provider->provide($channel->getId());

        return new SuccessResponse($scheduler);
    }
}
