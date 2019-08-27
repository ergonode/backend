<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Authentication\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Mock Controller overriding "magic" JWT authorization action. Required for showing proper description in NelmioApiDoc page
 */
final class AuthenticationController extends AbstractApiController
{
    /**
     * @Route("/api/v1/login", methods={"POST"})
     *
     * @SWG\Tag(name="Authorization")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Login action, return JWT token",
     *     @SWG\Schema (ref="#/definitions/credentials")
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns JWT token",
     *     @SWG\Schema (ref="#/definitions/authentication")
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request",
     *     @SWG\Schema (ref="#/definitions/error")
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @SWG\Schema (ref="#/definitions/error")
     * )
     */
    public function login(): void
    {
        throw new NotAcceptableHttpException();
    }
}
