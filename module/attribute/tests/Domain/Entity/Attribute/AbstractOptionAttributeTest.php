<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;

class AbstractOptionAttributeTest extends TestCase
{
    private AttributeId $id;

    private AttributeCode $code;

    private TranslatableString $translation;

    private AttributeScope $scope;

    public function setUp(): void
    {
        $this->id = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->translation = $this->createMock(TranslatableString::class);
        $this->scope = $this->createMock(AttributeScope::class);
    }

    public function testAddOptionAtTheEnd(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);

        self::assertTrue($attribute->hasOption($option1->getId()));
        self::assertTrue($attribute->hasOption($option2->getId()));
        self::assertSame($attribute->getOptions()[0], $option1->getId());
        self::assertSame($attribute->getOptions()[1], $option2->getId());
    }

    public function testMoveOptionAfterSelf(): void
    {
        $this->expectException(\DomainException::class);
        $attribute = $this->getClass();

        $option1 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->moveOption($option1, true, $option1);
    }

    public function testAddOptionAfterNotExistsOption(): void
    {
        $this->expectException(\DomainException::class);
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2, true, $option3);
    }

    public function testMoveOptionAfterNotExistsOption(): void
    {
        $this->expectException(\DomainException::class);
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->moveOption($option1, true, $option2);
    }

    public function testAddOptionAtTheBeginning(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();

        $attribute->addOption($option1, false);
        $attribute->addOption($option2, false);

        self::assertTrue($attribute->hasOption($option1->getId()));
        self::assertTrue($attribute->hasOption($option2->getId()));
        self::assertSame($attribute->getOptions()[0], $option2->getId());
        self::assertSame($attribute->getOptions()[1], $option1->getId());
    }

    public function testAddOptionAfterOption(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();
        $option = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->addOption($option, true, $option2);

        self::assertSame($attribute->getOptions()[2], $option->getId());
    }

    public function testAddOptionBeforeOption(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();
        $option = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->addOption($option, false, $option2);

        self::assertSame($attribute->getOptions()[1], $option->getId());
    }

    public function testRemoveOption(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();
        $option = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->addOption($option, true, $option2);
        self::assertTrue($attribute->hasOption($option->getId()));

        $attribute->removeOption($option);

        self::assertFalse($attribute->hasOption($option->getId()));
        self::assertSame($attribute->getOptions()[0], $option1->getId());
        self::assertSame($attribute->getOptions()[1], $option2->getId());
        self::assertSame($attribute->getOptions()[2], $option3->getId());
        self::assertSame($attribute->getOptions()[3], $option4->getId());
    }

    public function testMoveOptionAtTheEnd(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->moveOption($option1);

        self::assertSame($attribute->getOptions()[0], $option2->getId());
        self::assertSame($attribute->getOptions()[1], $option3->getId());
        self::assertSame($attribute->getOptions()[2], $option4->getId());
        self::assertSame($attribute->getOptions()[3], $option1->getId());
    }

    public function testMoveOptionToTheBeginning(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->moveOption($option4, false);

        self::assertSame($attribute->getOptions()[0], $option4->getId());
        self::assertSame($attribute->getOptions()[1], $option1->getId());
        self::assertSame($attribute->getOptions()[2], $option2->getId());
        self::assertSame($attribute->getOptions()[3], $option3->getId());
    }

    public function testMoveOptionAfterOption(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->moveOption($option4, true, $option1);

        self::assertSame($attribute->getOptions()[0], $option1->getId());
        self::assertSame($attribute->getOptions()[1], $option4->getId());
        self::assertSame($attribute->getOptions()[2], $option2->getId());
        self::assertSame($attribute->getOptions()[3], $option3->getId());
    }

    public function testMoveOptionBeforeOption(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->moveOption($option1, false, $option4);

        self::assertSame($attribute->getOptions()[0], $option2->getId());
        self::assertSame($attribute->getOptions()[1], $option3->getId());
        self::assertSame($attribute->getOptions()[2], $option1->getId());
        self::assertSame($attribute->getOptions()[3], $option4->getId());
    }

    public function testMoveOptionToSamePosition(): void
    {
        $attribute = $this->getClass();

        $option1 = $this->getOption();
        $option2 = $this->getOption();
        $option3 = $this->getOption();
        $option4 = $this->getOption();

        $attribute->addOption($option1);
        $attribute->addOption($option2);
        $attribute->addOption($option3);
        $attribute->addOption($option4);

        $attribute->moveOption($option3, true, $option2);

        self::assertSame($attribute->getOptions()[0], $option1->getId());
        self::assertSame($attribute->getOptions()[1], $option2->getId());
        self::assertSame($attribute->getOptions()[2], $option3->getId());
        self::assertSame($attribute->getOptions()[3], $option4->getId());
    }

    private function getClass(): AbstractOptionAttribute
    {
        return new class(
            $this->id,
            $this->code,
            $this->translation,
            $this->translation,
            $this->translation,
            $this->scope,
        ) extends AbstractOptionAttribute {
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }

    private function getOption(): AbstractOption
    {
        $option = $this->createMock(AbstractOption::class);
        $option->method('getId')->willReturn(AggregateId::generate());

        return $option;
    }
}
