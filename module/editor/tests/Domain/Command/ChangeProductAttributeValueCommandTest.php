<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChangeProductAttributeValueCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        /** @var ProductId|MockObject $productId*/
        $productId = $this->createMock(ProductId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $value = 'Any value';

        $command = new ChangeProductAttributeValueCommand($productId, $attributeId, $language, $value);
        $this->assertSame($productId, $command->getId());
        $this->assertSame($attributeId, $command->getAttributeId());
        $this->assertSame($language, $command->getLanguage());
        $this->assertSame($value, $value);
    }
}
