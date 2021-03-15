<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditedAtSystemAttributeTest extends TestCase
{
    public function testEntityCreation(): void
    {
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);

        $entity = new EditedAtSystemAttribute($label, $hint, $placeholder);

        $this->assertSame(EditedAtSystemAttribute::TYPE, $entity->getType());
        $this->assertTrue($entity->isSystem());
        $this->assertFalse($entity->isEditable());
        $this->assertSame($label, $entity->getLabel());
        $this->assertSame($hint, $entity->getHint());
        $this->assertSame($placeholder, $entity->getPlaceholder());
    }
}
