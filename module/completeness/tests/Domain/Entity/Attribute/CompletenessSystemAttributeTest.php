<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\Entity\Attribute;

use Ergonode\Completeness\Domain\Entity\Attribute\CompletenessSystemAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CompletenessSystemAttributeTest extends TestCase
{
    public function testEntityCreation(): void
    {
        /** @var TranslatableString | MockObject $label */
        $label = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $hint */
        $hint = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $placeholder */
        $placeholder = $this->createMock(TranslatableString::class);

        $entity = new CompletenessSystemAttribute($label, $hint, $placeholder);

        $this->assertSame(CompletenessSystemAttribute::TYPE, $entity->getType());
        $this->assertTrue($entity->isSystem());
        $this->assertSame($label, $entity->getLabel());
        $this->assertSame($hint, $entity->getHint());
        $this->assertSame($placeholder, $entity->getPlaceholder());
    }
}
