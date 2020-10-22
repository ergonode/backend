<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\Importer\Infrastructure\Action\Process\Product\Resolver\ImportProductImageAttributeStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportProductImageAttributeStrategyTest extends TestCase
{
    private MultimediaQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(MultimediaQueryInterface::class);
    }


    public function testIsSupported(): void
    {
        $strategy = new ImportProductImageAttributeStrategy($this->query);

        self::assertTrue($strategy->supported(new AttributeType(ImageAttribute::TYPE)));
        self::assertFalse($strategy->supported(new AttributeType('any other type')));
    }

    public function testEmptyValue(): void
    {
        $id = $this->createMock(AttributeId::class);
        $code = $this->createMock(AttributeCode::class);
        $value = new TranslatableString();

        $strategy = new ImportProductImageAttributeStrategy($this->query);
        $result = $strategy->build($id, $code, $value);

        self::assertEmpty($result->getValue());
    }

    public function testNotEmptyWithoutOptionValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = $this->createMock(AttributeId::class);
        $code = $this->createMock(AttributeCode::class);
        $value = new TranslatableString(['pl_PL' => 'value']);

        $strategy = new ImportProductImageAttributeStrategy($this->query);
        $strategy->build($id, $code, $value);
    }
}
