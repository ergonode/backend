<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeCreatedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var AttributeCode | MockObject $attributeCode */
        $attributeCode = $this->createMock(AttributeCode::class);
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);
        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);
        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);
        $multilingual = true;
        $type = 'string';
        $class = 'class';
        $parameters = [];
        $editable = false;
        $deletable = false;
        $system = true;

        $event = new AttributeCreatedEvent($attributeId, $attributeCode, $label, $hint, $placeholder, $multilingual, $type, $class, $parameters, $editable, $deletable, $system);

        $this->assertNotNull($event->getId());
        $this->assertSame($type, $event->getType());
        $this->assertSame($attributeCode, $event->getCode());
        $this->assertSame($class, $event->getClass());
        $this->assertSame($label, $event->getLabel());
        $this->assertSame($hint, $event->getHint());
        $this->assertSame($placeholder, $event->getPlaceholder());
        $this->assertTrue($event->isMultilingual());
        $this->assertSame($parameters, $event->getParameters());
        $this->assertSame($editable, $event->isEditable());
        $this->assertSame($deletable, $event->isDeletable());
        $this->assertTrue($event->isSystem());
    }
}
