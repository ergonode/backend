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
use Ergonode\Workflow\Infrastructure\Condition\UserIsWorkflowCondition;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Account\Domain\Entity\User;

class UserIsWorkflowConditionCalculator implements WorkflowConditionCalculatorInterface
{
    private UserRepositoryInterface $userRepository;

    private AuthenticatedUserProviderInterface $authenticatedUserProvider;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    ) {
        $this->userRepository = $userRepository;
        $this->authenticatedUserProvider = $authenticatedUserProvider;
    }

    public function supports(WorkflowConditionInterface $condition): bool
    {
        return $condition instanceof UserIsWorkflowCondition;
    }

    /**
     * @param UserIsWorkflowCondition $condition
     */
    public function calculate(AbstractProduct $product, WorkflowConditionInterface $condition, Language $language): bool
    {
        if (!$this->supports($condition)) {
            throw new WorkflowConditionCalculatorException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    UserIsWorkflowCondition::class,
                    get_debug_type($condition)
                )
            );
        }

        $user = $this->userRepository->load($condition->getUserId());

        Assert::isInstanceOf($user, User::class);

        $authenticatedUser = $this->authenticatedUserProvider->provide();
        if ($authenticatedUser->getId()->isEqual($user->getId())) {
            return true;
        }

        return false;
    }
}
