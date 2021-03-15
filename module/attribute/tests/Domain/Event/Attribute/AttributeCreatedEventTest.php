<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeCreatedEventTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testEventCreation(bool $system): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var AttributeCode | MockObject $attributeCode */
        $attributeCode = $this->createMock(AttributeCode::class);
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);
        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);
        /** @var AttributeScope | MockObject $scope */
        $scope = $this->createMock(AttributeScope::class);
        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);
        $type = 'string';
        $parameters = [];

        $event = new AttributeCreatedEvent(
            $attributeId,
            $attributeCode,
            $label,
            $hint,
            $placeholder,
            $scope,
            $type,
            $parameters,
            $system
        );

        $this::assertSame($type, $event->getType());
        $this::assertSame($attributeCode, $event->getCode());
        $this::assertSame($label, $event->getLabel());
        $this::assertSame($hint, $event->getHint());
        $this::assertSame($placeholder, $event->getPlaceholder());
        $this::assertSame($scope, $event->getScope());
        $this::assertSame($parameters, $event->getParameters());
        $this::assertSame($system, $event->isSystem());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'system' => true,
            ],
            [
                'system' => false,
            ],
        ];
    }
}
