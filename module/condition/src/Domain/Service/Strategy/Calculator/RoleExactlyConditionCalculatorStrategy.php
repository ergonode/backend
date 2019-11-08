<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy\Calculator;

use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Webmozart\Assert\Assert;

/**
 */
class RoleExactlyConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $authenticatedUserProvider;

    /**
     * @param RoleRepositoryInterface            $roleRepository
     * @param AuthenticatedUserProviderInterface $authenticatedUserProvider
     */
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
