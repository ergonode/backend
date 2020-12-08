<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\PasswordToken;

use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraint;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     name="ergonode_reset_token_validation",
 *     path="accounts/token/validation",
 *     methods={"POST"},
 *     )
 */
class UserRestPasswordTokenValidationAction
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
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
        $value = $request->request->get('token');

        $constraint = new AvailableTokenConstraint();

        $violations = $this->validator->validate($value, $constraint);
        if (0 === $violations->count()) {
            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
