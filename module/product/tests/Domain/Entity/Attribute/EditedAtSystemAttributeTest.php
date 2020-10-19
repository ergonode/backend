<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class EditedAtSystemAttributeTest extends TestCase
{
    /**
     */
    public function testEntityCreation(): void
    {
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);

        $entity = new EditedAtSystemAttribute($label, $hint, $placeholder);

        self::assertSame(EditedAtSystemAttribute::TYPE, $entity->getType());
        self::assertTrue($entity->isSystem());
        self::assertFalse($entity->isEditable());
        self::assertSame($label, $entity->getLabel());
        self::assertSame($hint, $entity->getHint());
        self::assertSame($placeholder, $entity->getPlaceholder());
    }
}
