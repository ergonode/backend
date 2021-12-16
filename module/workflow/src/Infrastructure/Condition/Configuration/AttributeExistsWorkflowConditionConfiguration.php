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
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Workflow\Domain\Condition\Configuration\Parameter\SelectWorkflowConditionConfigurationParameter;
use Ergonode\Workflow\Infrastructure\Condition\AttributeExistsWorkflowCondition;

class AttributeExistsWorkflowConditionConfiguration implements WorkflowConditionConfigurationInterface
{
    private AttributeQueryInterface $query;

    private TranslatorInterface $translator;

    public function __construct(AttributeQueryInterface $query, TranslatorInterface $translator)
    {
        $this->query = $query;
        $this->translator = $translator;
    }

    public function supports(string $type): bool
    {
        return AttributeExistsWorkflowCondition::TYPE === $type;
    }

    public function getConfiguration(Language $language): WorkflowConditionConfiguration
    {
        $code = $language->getCode();
        $codes = $this->query->getDictionary();
        asort($codes);

        $name = $this->translator->trans(AttributeExistsWorkflowCondition::TYPE, [], 'workflow', $code);
        $phrase = $this->translator->trans(AttributeExistsWorkflowCondition::PHRASE, [], 'workflow', $code);

        $parameters = [
            new SelectWorkflowConditionConfigurationParameter('attribute', $codes),
        ];

        return new WorkflowConditionConfiguration(
            AttributeExistsWorkflowCondition::TYPE,
            $name,
            $phrase,
            $parameters
        );
    }
}
