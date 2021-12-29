<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;

class ApiAuthTokenContext implements Context
{
    private Request $request;

    private string $authHeader;

    public function __construct(Request $request, string $authHeader)
    {
        $this->request = $request;
        $this->authHeader = $authHeader;
    }

    /**
     * @Given I use Authenticated token :token
     */
    public function iAmAuthenticatedAsToken(string $token): void
    {
        /** @phpstan-ignore-next-line */
        $this->request->setHttpHeader($this->authHeader, 'Bearer '.$token);
    }
}
