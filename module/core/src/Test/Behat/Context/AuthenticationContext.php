<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class AuthenticationContext implements Context
{
    private StorageContext $storageContext;

    private string $username;

    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->storageContext = $environment->getContext('Ergonode\Core\Test\Behat\Context\StorageContext');

        $this->storageContext->addDefinition('default_user_username', $this->username);
        $this->storageContext->addDefinition('default_user_password', $this->password);
    }
}
