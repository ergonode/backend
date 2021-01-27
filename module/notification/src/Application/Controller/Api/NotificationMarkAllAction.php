<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Application\Controller\Api;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Api\Application\Response\AcceptedResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Notification\Domain\Command\MarkAllNotificationsCommand;

/**
 * @Route("/profile/notifications/mark-all", methods={"POST"})
 */
class NotificationMarkAllAction
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
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        $user = $this->userProvider->provide();
        $command = new MarkAllNotificationsCommand($user->getId(), new \DateTime());

        $this->commandBud->dispatch($command);

        return new AcceptedResponse();
    }
}
