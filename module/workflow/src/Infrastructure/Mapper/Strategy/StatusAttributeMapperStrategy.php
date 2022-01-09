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

        $aggregate = $this->manager->load($aggregateId);
        Assert::isInstanceOf($aggregate, AbstractProduct::class);

        foreach ($values as $key => $value) {
            if (null !== $value) {
                Assert::stringNotEmpty($value);
                Assert::uuid($value);

                /** @var AbstractProduct $aggregate */
                $this->statusValidation($key, $aggregate, $value);
            }
        }

        return new TranslatableStringValue(new TranslatableString($values));
    }

    private function statusValidation(string $key, AbstractProduct $aggregate, string $value): void
    {
        $language = new Language($key);
        $workflow = $this->workflowProvider->provide($language);
        $statusIds = $this->query->getAvailableStatuses($aggregate, $workflow, $language);

        if (!in_array($value, $statusIds, true)) {
            throw new \InvalidArgumentException('This status can\'t be set.');
        }
    }
}
