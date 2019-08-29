<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class AuthenticationContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var StorageContext
     */
    private $storageContext;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->apiContext = $environment->getContext('ApiContext');
        $this->storageContext = $environment->getContext('StorageContext');

        $this->storageContext->addDefinition('default_user_username', $this->username);
        $this->storageContext->addDefinition('default_user_password', $this->password);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @Given Authenticate as user :username with password :password
     */
    public function login(string $username, string $password): void
    {
        $body = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        $this->apiContext->requestPath('/api/v1/login', Request::METHOD_POST);
        $this->apiContext->setRequestBody($body);
        $this->apiContext->requestSend();
        $this->apiContext->rememberResponseParam('token', 'token');
    }

    /**
     * @Given current authentication token
     */
    public function authenticationToken(): void
    {
        if (!$this->storageContext->has('token')) {
            $this->login($this->username, $this->password);
        }

        $this->apiContext->setRequestHeader('JWTAuthorization', sprintf(
            'Bearer %s',
            $this->storageContext->get('token')
        ));
    }
}
