<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Tests\Domain\Command;

use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PersistProductDraftCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        /** @var ProductDraftId|MockObject $draftId */
        $draftId = $this->createMock(ProductDraftId::class);

        $command = new PersistProductDraftCommand($draftId);
        $this->assertEquals($draftId, $command->getId());
    }
}
