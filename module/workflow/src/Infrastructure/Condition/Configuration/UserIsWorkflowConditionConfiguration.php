<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Configuration;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\Parameter\SelectWorkflowConditionConfigurationParameter;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Workflow\Infrastructure\Condition\UserIsWorkflowCondition;

class UserIsWorkflowConditionConfiguration implements WorkflowConditionConfigurationInterface
{
    private TranslatorInterface $translator;

    private UserQueryInterface $query;

    public function __construct(TranslatorInterface $translator, UserQueryInterface $query)
    {
        $this->translator = $translator;
        $this->query = $query;
    }

    public function supports(string $type): bool
    {
        return UserIsWorkflowCondition::TYPE === $type;
    }

    public function getConfiguration(Language $language): WorkflowConditionConfiguration
    {
        $code = $language->getCode();
        $users = $this->query->getDictionary();

        $name = $this->translator->trans(UserIsWorkflowCondition::TYPE, [], 'workflow', $code);
        $phrase = $this->translator->trans(UserIsWorkflowCondition::PHRASE, [], 'workflow', $code);

        $parameters = [
            new SelectWorkflowConditionConfigurationParameter('user', $users),
        ];

        return new WorkflowConditionConfiguration(
            UserIsWorkflowCondition::TYPE,
            $name,
            $phrase,
            $parameters
        );
    }
}
