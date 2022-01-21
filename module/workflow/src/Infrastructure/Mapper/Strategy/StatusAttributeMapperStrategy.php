<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\ContextAwareAttributeMapperStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class StatusAttributeMapperStrategy implements ContextAwareAttributeMapperStrategyInterface
{
    private EventStoreManagerInterface $manager;

    private ProductWorkflowQuery $query;

    private WorkflowProviderInterface $workflowProvider;

    public function __construct(
        EventStoreManagerInterface $manager,
        ProductWorkflowQuery $query,
        WorkflowProviderInterface $workflowProvider
    ) {
        $this->manager = $manager;
        $this->query = $query;
        $this->workflowProvider = $workflowProvider;
    }

    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === StatusSystemAttribute::TYPE;
    }

    public function map(array $values, ?AggregateId $aggregateId = null): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');
        Assert::notNull($aggregateId);

        /** @var AbstractProduct $aggregate */
        $aggregate = $this->manager->load($aggregateId);
        Assert::isInstanceOf($aggregate, AbstractProduct::class);

        foreach ($values as $code => $value) {
            if (null !== $value) {
                Assert::stringNotEmpty($value);
                Assert::uuid($value);
                $this->statusValidation(new Language($code), $aggregate, $value);
            }
        }

        return new TranslatableStringValue(new TranslatableString($values));
    }

    private function statusValidation(Language $language, AbstractProduct $aggregate, string $value): void
    {
        $attributeCode = new AttributeCode(StatusSystemAttribute::CODE);
        $workflow = $this->workflowProvider->provide($language);

        if ($aggregate->hasAttribute($attributeCode)
            && $aggregate->getAttribute($attributeCode)->hasTranslation($language)) {
            $statusIds = $this->query->getAvailableStatuses($aggregate, $workflow, $language);
        } else {
            $statusIds = [$workflow->getDefaultStatus()->getValue()];
        }

        if (!in_array($value, $statusIds, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Can\'t set "%s" status in "%s" language for product "%s".',
                    $value,
                    $language->getCode(),
                    $aggregate->getId()->getValue(),
                )
            );
        }
    }
}
