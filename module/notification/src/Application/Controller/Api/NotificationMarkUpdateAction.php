<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Application\Controller\Api;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Api\Application\Response\AcceptedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\MarkNotificationCommand;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/notifications/{notification}/mark", methods={"POST"})
 */
class NotificationMarkUpdateAction
{
    private AuthenticatedUserProviderInterface $userProvider;

    private CommandBusInterface $commandBud;

    public function __construct(AuthenticatedUserProviderInterface $userProvider, CommandBusInterface $commandBud)
    {
        $this->userProvider = $userProvider;
        $this->commandBud = $commandBud;
    }

    /**
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="notification",
     *     in="path",
     *     type="string",
     *     description="Notification id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns notifications",
     * )
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(string $notification): Response
    {
        $user = $this->userProvider->provide();
        $command = new MarkNotificationCommand(Uuid::fromString($notification), $user->getId(), new \DateTime());

        $this->commandBud->dispatch($command);

        return new AcceptedResponse();
    }
}
