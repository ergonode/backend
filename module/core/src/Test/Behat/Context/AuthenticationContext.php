<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use FriendsOfBehat\SymfonyExtension\Context\Environment\InitializedSymfonyExtensionEnvironment;

class AuthenticationContext implements Context
{

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
        /** @var InitializedSymfonyExtensionEnvironment $environment */
        $environment = $scope->getEnvironment();
        /** @var StorageContext $storageContext */
        $storageContext = $environment->getContext(StorageContext::class);

        $storageContext->addDefinition('default_user_username', $this->username);
        $storageContext->addDefinition('default_user_password', $this->password);
    }
}
