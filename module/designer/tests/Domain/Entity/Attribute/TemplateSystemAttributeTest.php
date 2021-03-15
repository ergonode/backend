<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateSystemAttributeTest extends TestCase
{
    public function testEntityCreation(): void
    {
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);

        $entity = new TemplateSystemAttribute($label, $hint, $placeholder);

        $this->assertSame(TemplateSystemAttribute::TYPE, $entity->getType());
        $this->assertTrue($entity->isSystem());
        $this->assertFalse($entity->isEditable());
        $this->assertSame($label, $entity->getLabel());
        $this->assertSame($hint, $entity->getHint());
        $this->assertSame($placeholder, $entity->getPlaceholder());
    }
}
