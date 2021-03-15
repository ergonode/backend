<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Attribute;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributeCommand;

class RemoveProductAttributeValueCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        /** @var ProductId|MockObject $productId */
        $productId = $this->createMock(ProductId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);

        $command = new RemoveProductAttributeCommand($productId, $attributeId, $language);
        $this->assertSame($productId, $command->getId());
        $this->assertSame($attributeId, $command->getAttributeId());
        $this->assertSame($language, $command->getLanguage());
    }
}
