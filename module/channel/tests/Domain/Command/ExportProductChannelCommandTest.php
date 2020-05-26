<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\Channel\Domain\Command\ExportProductChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ExportProductChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    /**
     * @var ProductId|MockObject
     */
    private ProductId $productId;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->productId = $this->createMock(ProductId::class);
    }

    /**
     */
    public function testCreateCommand(): void
    {
        $command = new ExportProductChannelCommand(
            $this->id,
            $this->productId
        );

        $this->assertEquals($this->id, $command->getChannelId());
        $this->assertEquals($this->productId, $command->getProductId());
    }
}
