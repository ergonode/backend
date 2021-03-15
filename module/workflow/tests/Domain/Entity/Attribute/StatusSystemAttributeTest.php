<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Entity\Attribute;

use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class StatusSystemAttributeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $label = $this->createMock(TranslatableString::class);
        $hint = $this->createMock(TranslatableString::class);
        $placeholder = $this->createMock(TranslatableString::class);

        $attribute = new StatusSystemAttribute($label, $hint, $placeholder);
        $scope = new AttributeScope(AttributeScope::LOCAL);

        self::assertSame($label, $attribute->getLabel());
        self::assertSame($placeholder, $attribute->getPlaceholder());
        self::assertSame($hint, $attribute->getHint());
        self::assertTrue($attribute->isSystem());
        self::assertEquals($scope, $attribute->getScope());
        self::assertSame(StatusSystemAttribute::TYPE, $attribute->getType());
    }
}
