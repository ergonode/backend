<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Condition\Domain\Condition\UserExactlyCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Webmozart\Assert\Assert;

/**
 */
class UserExactlyConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $authenticatedUserProvider;

    /**
     * @param UserRepositoryInterface            $userRepository
     * @param AuthenticatedUserProviderInterface $authenticatedUserProvider
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    ) {
        $this->userRepository = $userRepository;
        $this->authenticatedUserProvider = $authenticatedUserProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return UserExactlyCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $user = $this->userRepository->load($configuration->getUser());
        Assert::notNull($user);

        $result = false;
        try {
            $authenticatedUser = $this->authenticatedUserProvider->provide();
            if ($authenticatedUser->getId()->isEqual($user->getId())) {
                $result = true;
            }
        } catch (AuthenticationException $exception) {
            $result = true;
        }

        return $result;
    }
}
