<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;
use Ergonode\Workflow\Infrastructure\Mapper\Strategy\StatusAttributeMapperStrategy;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ramsey\Uuid\Uuid;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class StatusAttributeMapperStrategyTest extends TestCase
{
    private EventStoreManagerInterface $manager;

    private ProductWorkflowQuery $query;

    private WorkflowProviderInterface $workflowProvider;

    public function setUp(): void
    {
        $this->manager = $this->createMock(EventStoreManagerInterface::class);
        $this->query = $this->createMock(ProductWorkflowQuery::class);
        $this->workflowProvider = $this->createMock(WorkflowProviderInterface::class);
    }

    public function testSupported(): void
    {
        $type = new AttributeType(StatusSystemAttribute::TYPE);
        $strategy = new StatusAttributeMapperStrategy($this->manager, $this->query, $this->workflowProvider);
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidExistsMapping(array $values, ValueInterface $result, string $uuid): void
    {
        $value = $this->createMock(ValueInterface::class);
        $value->method('hasTranslation')->willReturn(true);
        $product = $this->createMock(AbstractProduct::class);
        $product->method('hasAttribute')->willReturn(true);
        $product->method('getAttribute')->willReturn($value);

        $this->manager->method('load')->willReturn($product);
        $this->workflowProvider->method('provide')->willReturn($this->createMock(AbstractWorkflow::class));
        $this->query->expects(self::once())->method('getAvailableStatuses')->willReturn([$uuid]);
        $strategy = new StatusAttributeMapperStrategy($this->manager, $this->query, $this->workflowProvider);
        $mapped = $strategy->map($values, $this->createMock(AggregateId::class));

        self::assertEquals($result, $mapped);
    }

    public function testValidNotExistMapping(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $values = ['en_GB' => $uuid];
        $result =  new TranslatableStringValue(new TranslatableString($values));
        $status = new StatusId($uuid);

        $workflow = $this->createMock(AbstractWorkflow::class);
        $workflow->method('getDefaultStatus')->willReturn($status);

        $product = $this->createMock(AbstractProduct::class);
        $product->method('hasAttribute')->willReturn(false);

        $this->workflowProvider->method('provide')->willReturn($workflow);

        $this->manager->method('load')->willReturn($product);
        $this->workflowProvider->method('provide')->willReturn($this->createMock(AbstractWorkflow::class));
        $this->query->expects(self::never())->method('getAvailableStatuses')->willReturn([$uuid]);
        $strategy = new StatusAttributeMapperStrategy($this->manager, $this->query, $this->workflowProvider);
        $mapped = $strategy->map($values, $this->createMock(AggregateId::class));

        self::assertEquals($result, $mapped);
    }

    public function testValidMappingWithNull(): void
    {
        $this->manager->method('load')->willReturn($this->createMock(AbstractProduct::class));

        $strategy = new StatusAttributeMapperStrategy($this->manager, $this->query, $this->workflowProvider);
        $mapped = $strategy->map(['pl_PL' => null], $this->createMock(AggregateId::class));

        self::assertEquals(new TranslatableStringValue(new TranslatableString(['pl_PL' => null])), $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(
        ?AbstractProduct $product,
        AbstractWorkflow $workflow,
        string $statusId,
        array $values
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->manager->method('load')->willReturn($product);
        $this->workflowProvider->method('provide')->willReturn($workflow);
        $this->query->method('getAvailableStatuses')->willReturn([$statusId]);
        $strategy = new StatusAttributeMapperStrategy($this->manager, $this->query, $this->workflowProvider);
        $strategy->map($values, $this->createMock(AggregateId::class));
    }

    public function getValidData(): array
    {
        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        return
            [
                [
                    ['pl_PL' => $uuid1],
                    new TranslatableStringValue(new TranslatableString(['pl_PL' => $uuid1])),
                    $uuid1,
                ],
                [
                    ['pl_PL' => $uuid2],
                    new TranslatableStringValue(new TranslatableString(['pl_PL' => $uuid2])),
                    $uuid2,
                ],
            ];
    }

    public function getInvalidData(): array
    {
        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        return [
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl' => 'string'],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['' => 'string'],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['' => ''],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_PL' => 0.0],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_PL' => 0],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_PL' => []],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_pl' => str_repeat('a', 257)],
            ],
            [
                null,
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_PL' => $uuid1],
            ],
            [
                $this->createMock(AbstractProduct::class),
                $this->createMock(AbstractWorkflow::class),
                $uuid1,
                ['pl_PL' => $uuid2],
            ],
        ];
    }
}
