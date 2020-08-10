<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route("/accounts/{user}/avatar", methods={"PUT"}, requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
 */
class AvatarChangeAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("USER_UPDATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User ID"
     * )
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="formData",
     *     type="string",
     *     description="Multimedia ID"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(User $user, Request $request): Response
    {
        $multimediaId = $request->request->get('multimedia');
        $multimediaId = $multimediaId ? new MultimediaId($multimediaId) : null;
        $command = new ChangeUserAvatarCommand($user->getId(), $multimediaId);
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
