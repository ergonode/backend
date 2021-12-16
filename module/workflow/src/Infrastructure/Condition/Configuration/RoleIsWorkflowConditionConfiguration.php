<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Configuration;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\Parameter\SelectWorkflowConditionConfigurationParameter;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;

class RoleIsWorkflowConditionConfiguration implements WorkflowConditionConfigurationInterface
{
    private TranslatorInterface $translator;

    private RoleQueryInterface $query;

    public function __construct(TranslatorInterface $translator, RoleQueryInterface $query)
    {
        $this->translator = $translator;
        $this->query = $query;
    }

    public function supports(string $type): bool
    {
        return RoleExactlyCondition::TYPE === $type;
    }

    public function getConfiguration(Language $language): WorkflowConditionConfiguration
    {
        $code = $language->getCode();
        $roles = $this->query->getDictionary();


        $name = $this->translator->trans(RoleExactlyCondition::TYPE, [], 'workflow', $code);
        $phrase = $this->translator->trans(RoleExactlyCondition::PHRASE, [], 'workflow', $code);

        $parameters = [
            new SelectWorkflowConditionConfigurationParameter('role', $roles),
        ];

        return new WorkflowConditionConfiguration(
            RoleExactlyCondition::TYPE,
            $name,
            $phrase,
            $parameters
        );
    }
}
