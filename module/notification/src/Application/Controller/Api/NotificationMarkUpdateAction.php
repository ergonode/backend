<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Application\Controller\Api;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\MarkNotificationCommand;
use Ramsey\Uuid\Uuid;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Uuid as UuidConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/profile/notifications/{notification}/mark", methods={"POST"})
 */
class NotificationMarkUpdateAction
{
    private AuthenticatedUserProviderInterface $userProvider;

    private CommandBusInterface $commandBud;

    private ValidatorInterface $validator;

    public function __construct(
        AuthenticatedUserProviderInterface $userProvider,
        CommandBusInterface $commandBud,
        ValidatorInterface $validator
    ) {
        $this->userProvider = $userProvider;
        $this->commandBud = $commandBud;
        $this->validator = $validator;
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
     *     response=204,
     *     description="Marked",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(string $notification): void
    {
        $user = $this->userProvider->provide();
        $violations = $this->validator->validate($notification, new UuidConstraint());

        if ($violations->count() === 0) {
            $command = new MarkNotificationCommand(Uuid::fromString($notification), $user->getId(), new \DateTime());

            $this->commandBud->dispatch($command);

            return;
        }
        throw new ViolationsHttpException($violations);
    }
}
