<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\PasswordToken;

use Ergonode\Account\Application\Form\GenerateUserTokenForm;
use Ergonode\Account\Application\Form\Model\GenerateUserTokenModel;
use Ergonode\Account\Domain\Command\User\GenerateUserResetPasswordTokenCommand;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_reset_token_generate",
 *     path="accounts/token/generate",
 *     methods={"POST"},
 *     )
 */
class UserResetPasswordTokenGenerateAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    private UserQueryInterface $query;


    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        UserQueryInterface $query
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User email",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account_token_generate")
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
        $model = new GenerateUserTokenModel();
        $form = $this->formFactory->create(GenerateUserTokenForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GenerateUserTokenModel $data */
            $data = $form->getData();
            $userId = $this->query->findIdByEmail(new Email($data->email));
            $path = $data->url;
            if ($userId) {
                $this->commandBus->dispatch(new GenerateUserResetPasswordTokenCommand($userId, $path));
            }

            return new EmptyResponse();
        }
        throw new FormValidationHttpException($form);
    }
}
