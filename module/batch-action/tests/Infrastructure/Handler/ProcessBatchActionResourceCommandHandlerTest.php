<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Infrastructure\Handler\ProcessBatchActionResourceCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolver;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionResourceCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProcessBatchActionResourceCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $batchActionRepository;

    private ProductRepositoryInterface $productRepository;

    private RelationshipsResolver $relationshipResolver;

    private ProcessBatchActionResourceCommand $command;

    protected function setUp(): void
    {
        $this->batchActionRepository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->relationshipResolver = $this->createMock(RelationshipsResolver::class);
        $this->command = $this->createMock(ProcessBatchActionResourceCommand::class);
        $this->command->expects(self::once())->method('getResourceId')->willReturn(AggregateId::generate());
    }

    public function testCommandHandlingWithoutRelation(): void
    {
        $this->productRepository->method('load')->willReturn($this->createMock(AbstractProduct::class));
        $this->productRepository->expects(self::never())->method('delete');
        $handler = new ProcessBatchActionResourceCommandHandler(
            $this->batchActionRepository,
            $this->productRepository,
            $this->relationshipResolver
        );
        $handler->__invoke($this->command);
    }
}