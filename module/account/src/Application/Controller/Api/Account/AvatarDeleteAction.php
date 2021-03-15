<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Command\User\DeleteUserAvatarCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route(
 *     name="ergonode_avatar_delete",
 *     path="/accounts/{user}/avatar",
 *     methods={"DELETE"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 *     )
 */
class AvatarDeleteAction
{
    private CommandBusInterface $commandBus;


    public function __construct(
        CommandBusInterface $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("ACCOUNT_DELETE_AVATAR")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User ID",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success",
     * )
     */
    public function __invoke(User $user, Request $request): Response
    {
        $command = new DeleteUserAvatarCommand($user->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
