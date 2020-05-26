<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Tests\Domain\Command;

use Ergonode\Editor\Domain\Command\RemoveProductAttributeValueCommand;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class RemoveProductAttributeValueCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        /** @var ProductDraftId|MockObject $draftId */
        $draftId = $this->createMock(ProductDraftId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);

        $command = new RemoveProductAttributeValueCommand($draftId, $attributeId, $language);
        $this->assertSame($draftId, $command->getId());
        $this->assertSame($attributeId, $command->getAttributeId());
        $this->assertSame($language, $command->getLanguage());
    }
}
