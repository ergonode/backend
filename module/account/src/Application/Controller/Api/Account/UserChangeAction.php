<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Application\Form\Model\UpdateUserFormModel;
use Ergonode\Account\Application\Form\UpdateUserForm;
use Ergonode\Account\Domain\Command\User\UpdateUserCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

/**
 * @Route(
 *     name="ergonode_account_user_change",
 *     path="/accounts/{user}",
 *     methods={"PUT"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class UserChangeAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ACCOUNT_PUT")
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
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account_update")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language code"
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
     */
    public function __invoke(User $user, Request $request): Response
    {
        try {
            $model = new UpdateUserFormModel();
            $form = $this->formFactory->create(UpdateUserForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UpdateUserFormModel $data */
                $data = $form->getData();
                $password = $data->password ? new Password($data->password) : null;
                $command = new UpdateUserCommand(
                    $user->getId(),
                    $data->firstName,
                    $data->lastName,
                    $data->language,
                    new RoleId($data->roleId),
                    $data->languagePrivilegesCollection,
                    $data->isActive,
                    $password
                );
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
