<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;

class ApiAuthTokenContext implements Context
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @Given I use Authenticated token :token
     */
    public function iAmAuthenticatedAsUser(string $token): void
    {
        $this->request->setHttpHeader('JWTAuthorization', 'Bearer '.$token);
    }
}
