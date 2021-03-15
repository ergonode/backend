<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Controller\Api;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Mock Controller overriding "magic" JWT authorization action.
 * Required for showing proper description in NelmioApiDoc page
 *
 * @Route("/api/v1/login", methods={"POST"})
 */
final class AuthenticationAction
{
    /**
     * @SWG\Tag(name="Authorization")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Login action, return JWT token",
     *     @SWG\Schema(ref="#/definitions/credentials")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns JWT token",
     *     @SWG\Schema (ref="#/definitions/authentication")
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *     @SWG\Schema(ref="#/definitions/error_response")
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @SWG\Schema(ref="#/definitions/error_response")
     * )
     */
    public function __invoke(): void
    {
        throw new NotAcceptableHttpException();
    }
}
