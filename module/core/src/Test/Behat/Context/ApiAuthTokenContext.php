<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Psr\Container\ContainerInterface;

class ApiAuthTokenContext implements Context
{
    private string $authHeader;
    private ContainerInterface $container;

    public function __construct(string $authHeader, ContainerInterface $container)
    {
        $this->authHeader = $authHeader;
        $this->container = $container;
    }


    /**
     * @Given I use Authenticated token :token
     */
    public function iAmAuthenticatedAsToken(string $token): void
    {
        $this->container
            ->get('behat.service_container')
            ->get('behatch.http_call.request')
            ->setHttpHeader($this->authHeader, 'Bearer '.$token);
    }
}
