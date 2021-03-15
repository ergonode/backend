<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Webmozart\Assert\Assert;

class RoleExactlyConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private RoleRepositoryInterface $roleRepository;

    private AuthenticatedUserProviderInterface $authenticatedUserProvider;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    ) {
        $this->roleRepository = $roleRepository;
        $this->authenticatedUserProvider = $authenticatedUserProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return RoleExactlyCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof RoleExactlyCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    RoleExactlyCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $role = $this->roleRepository->load($configuration->getRole());
        Assert::notNull($role);


        $result = false;
        try {
            $authenticatedUser = $this->authenticatedUserProvider->provide();

            if ($authenticatedUser->getRoleId()->isEqual($role->getId())) {
                $result = true;
            }
        } catch (AuthenticationException $exception) {
            $result = true;
        }

        return $result;
    }
}
