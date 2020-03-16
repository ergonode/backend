<?php

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;
use Ergonode\Account\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 */
class ApiAuthContext implements Context
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTTokenManager;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param JWTTokenManagerInterface $JWTTokenManager
     * @param Request                  $request
     */
    public function __construct(
        JWTTokenManagerInterface $JWTTokenManager,
        Request $request
    ) {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->request         = $request;
    }

    /**
     * @Given I am Authenticated as :user
     *
     * @param User $user
     */
    public function iAmAuthenticatedAsUser(User $user): void
    {
        $token = $this->JWTTokenManager->create($user);
        $this->request->setHttpHeader('JWTAuthorization', 'Bearer '.$token);
    }
}
