<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\PasswordToken;

use Ergonode\Account\Application\Form\Model\UserApplyTokenModel;
use Ergonode\Account\Application\Form\UserApplyTokenForm;
use Ergonode\Account\Domain\Command\User\ApplyUserResetTokenCommand;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_reset_token_apply",
 *     path="accounts/token/apply",
 *     methods={"POST"},
 *     )
 */
class UserRestPasswordTokenApplyAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User email",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account_token_apply")
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
    public function __invoke(Request $request): Response
    {
        $model = new UserApplyTokenModel();
        $form = $this->formFactory->create(UserApplyTokenForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserApplyTokenModel $data */
            $data = $form->getData();
            $command = new ApplyUserResetTokenCommand(
                new ResetToken($data->token),
                new Password($data->password)
            );
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }
        throw new FormValidationHttpException($form);
    }
}
