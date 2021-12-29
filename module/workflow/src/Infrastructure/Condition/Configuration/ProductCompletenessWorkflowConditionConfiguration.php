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
use Ergonode\Workflow\Infrastructure\Condition\ProductCompletenessWorkflowCondition;
use Ergonode\Workflow\Domain\Condition\Configuration\Parameter\SelectWorkflowConditionConfigurationParameter;

class ProductCompletenessWorkflowConditionConfiguration implements WorkflowConditionConfigurationInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function supports(string $type): bool
    {
        return ProductCompletenessWorkflowCondition::TYPE === $type;
    }

    public function getConfiguration(Language $language): WorkflowConditionConfiguration
    {
        $code = $language->getCode();
        $options = [
            ProductCompletenessWorkflowCondition::COMPLETE => $this
                ->translator
                ->trans(
                    ProductCompletenessWorkflowCondition::PRODUCT_COMPLETE,
                    [],
                    'workflow',
                    $language->getCode()
                ),
            ProductCompletenessWorkflowCondition::NOT_COMPLETE => $this
                ->translator
                ->trans(
                    ProductCompletenessWorkflowCondition::PRODUCT_NOT_COMPLETE,
                    [],
                    'workflow',
                    $language->getCode()
                ),
        ];

        $name = $this->translator->trans(ProductCompletenessWorkflowCondition::TYPE, [], 'workflow', $code);
        $phrase = $this->translator->trans(ProductCompletenessWorkflowCondition::PHRASE, [], 'workflow', $code);

        $parameters = [
            new SelectWorkflowConditionConfigurationParameter('completeness', $options),
        ];

        return new WorkflowConditionConfiguration(
            ProductCompletenessWorkflowCondition::TYPE,
            $name,
            $phrase,
            $parameters
        );
    }
}
