<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\ReadModel;

use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CompletenessReadModelTest extends TestCase
{
    /**
     */
    public function testModelCreation(): void
    {
        /** @var Language|MockObject $attributeId */
        $language = $this->createMock(Language::class);

        $model = new CompletenessReadModel($language);

        self::assertEquals(0, $model->getFilled());
        self::assertEquals($language, $model->getLanguage());
        self::assertEquals(0, $model->getRequired());
        self::assertEquals([], $model->getMissing());
        self::assertEquals(100, $model->getPercent());
    }

    /**
     */
    public function testModelAddNotRequiredElementModel(): void
    {
        $element = $this->createMock(CompletenessElementReadModel::class);
        /** @var Language|MockObject $attributeId */
        $language = $this->createMock(Language::class);

        $model = new CompletenessReadModel($language);
        $model->addCompletenessElement($element);

        self::assertEquals(0, $model->getFilled());
        self::assertEquals($language, $model->getLanguage());
        self::assertEquals(0, $model->getRequired());
        self::assertEquals([], $model->getMissing());
        self::assertEquals(100, $model->getPercent());
    }

    /**
     */
    public function testModelAddRequiredElementModel(): void
    {
        $element = $this->createMock(CompletenessElementReadModel::class);
        $element->method('isRequired')->willReturn(true);
        /** @var Language|MockObject $attributeId */
        $language = $this->createMock(Language::class);

        $model = new CompletenessReadModel($language);
        $model->addCompletenessElement($element);

        self::assertEquals(0, $model->getFilled());
        self::assertEquals($language, $model->getLanguage());
        self::assertEquals(1, $model->getRequired());
        self::assertEquals([$element], $model->getMissing());
        self::assertEquals(0, $model->getPercent());
    }

    /**
     */
    public function testModelAddRequiredAndFilledElementModel(): void
    {
        $element = $this->createMock(CompletenessElementReadModel::class);
        $element->method('isRequired')->willReturn(true);
        $element->method('isFilled')->willReturn(true);
        /** @var Language|MockObject $attributeId */
        $language = $this->createMock(Language::class);

        $model = new CompletenessReadModel($language);
        $model->addCompletenessElement($element);

        self::assertEquals(1, $model->getFilled());
        self::assertEquals($language, $model->getLanguage());
        self::assertEquals(1, $model->getRequired());
        self::assertEquals([], $model->getMissing());
        self::assertEquals(100, $model->getPercent());
    }
}
