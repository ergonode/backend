<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\PasswordToken;

use Ergonode\Account\Application\Form\Model\UserTokenModel;
use Ergonode\Account\Application\Form\UserTokenForm;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_reset_token_validation",
 *     path="accounts/token/validation",
 *     methods={"POST"},
 *     )
 */
class UserRestPasswordTokenValidationAction
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User email",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account_token_validation")
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
        $model = new UserTokenModel();
        $form = $this->formFactory->create(UserTokenForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new EmptyResponse();
        }
        throw new FormValidationHttpException($form);
    }
}
