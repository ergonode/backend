<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\ReadModel;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CompletenessElementReadModelTest extends TestCase
{
    /**
     */
    public function testProperCreation(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $name = 'Any Name';
        $required = false;
        $value = 'Any Value';

        $model = new CompletenessElementReadModel($attributeId, $name, $required, $value);

        $this->assertEquals($attributeId, $model->getId());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($required, $model->isRequired());
        $this->assertTrue($model->isFilled());
    }
}
