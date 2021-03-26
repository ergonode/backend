<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\PasswordToken;

use Ergonode\Account\Domain\Validator\TokenValidator;
use Ergonode\Api\Application\Response\EmptyResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_reset_token_validation",
 *     path="accounts/token/validation",
 *     methods={"GET"},
 *     )
 */
class UserRestPasswordTokenValidationAction
{
    private TokenValidator $validator;

    public function __construct(TokenValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="token",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Token"
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
        $value = $request->query->get('token');

        $this->validator->validate($value);

        if ($this->validator->validate($value)) {
            return new EmptyResponse();
        }

        throw new BadRequestHttpException('Validation error');
    }
}
