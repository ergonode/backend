<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Calculator;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionCalculatorInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Infrastructure\Exception\WorkflowConditionCalculatorException;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Workflow\Infrastructure\Condition\RoleIsWorkflowCondition;

class RoleIsWorkflowConditionCalculator implements WorkflowConditionCalculatorInterface
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

    public function supports(WorkflowConditionInterface $condition): bool
    {
        return $condition instanceof RoleIsWorkflowCondition;
    }

    /**
     * @param RoleIsWorkflowCondition $condition
     */
    public function calculate(AbstractProduct $product, WorkflowConditionInterface $condition, Language $language): bool
    {
        if (!$this->supports($condition)) {
            throw new WorkflowConditionCalculatorException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    RoleIsWorkflowCondition::class,
                    get_debug_type($condition)
                )
            );
        }

        $role = $this->roleRepository->load($condition->getRoleId());

        Assert::isInstanceOf($role, Role::class);

        $result = false;
        $authenticatedUser = $this->authenticatedUserProvider->provide();

        if ($authenticatedUser->getRoleId()->isEqual($role->getId())) {
            $result = true;
        }

        return $result;
    }
}
