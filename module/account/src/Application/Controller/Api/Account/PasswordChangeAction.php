<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\Builder\PasswordValidationBuilder;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var PasswordValidationBuilder
     */
    private PasswordValidationBuilder $builder;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @param PasswordValidationBuilder          $builder
     * @param MessageBusInterface                $messageBus
     * @param ValidatorInterface                 $validator
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(
        PasswordValidationBuilder $builder,
        MessageBusInterface $messageBus,
        ValidatorInterface $validator,
        AuthenticatedUserProviderInterface $userProvider
    ) {
        $this->builder = $builder;
        $this->messageBus = $messageBus;
        $this->validator = $validator;
        $this->userProvider = $userProvider;
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
     *     default="EN",
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
        $data = $request->request->all();
        $constraint = $this->builder->create($data);
        $violations = $this->validator->validate($data, $constraint);
        $userId = $this->userProvider->provide()->getId();

        if ($violations->count() === 0) {
            $command = new ChangeUserPasswordCommand($userId, new Password((string) $data['password']));
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
