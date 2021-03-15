<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\Builder\PasswordValidationBuilder;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_account_password_change",
 *     path="/accounts/{user}/password",
 *     methods={"PUT"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class PasswordChangeAction
{
    private PasswordValidationBuilder $builder;

    private CommandBusInterface $commandBus;

    private ValidatorInterface $validator;

    private AuthenticatedUserProviderInterface $userProvider;

    public function __construct(
        PasswordValidationBuilder $builder,
        CommandBusInterface $commandBus,
        ValidatorInterface $validator,
        AuthenticatedUserProviderInterface $userProvider
    ) {
        $this->builder = $builder;
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->userProvider = $userProvider;
    }

    /**
     * @IsGranted("ACCOUNT_PUT_PASSWORD")
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
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Change password",
     *     @SWG\Schema(ref="#/definitions/account_change_password")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(User $user, Request $request): Response
    {
        $data = $request->request->all();
        $constraint = $this->builder->create($data);
        $violations = $this->validator->validate($data, $constraint);
        $userId = $this->userProvider->provide()->getId();

        if ($violations->count() === 0) {
            $command = new ChangeUserPasswordCommand($userId, new Password((string) $data['password']));
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
