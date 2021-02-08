<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy\ImportProductPriceAttributeStrategy;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use PHPUnit\Framework\TestCase;

class ImportProductPriceAttributeStrategyTest extends TestCase
{
    private ImportProductPriceAttributeStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ImportProductPriceAttributeStrategy();
    }

    public function testShouldBuild(): void
    {
        $id = $this->createMock(AttributeId::class);
        $code = $this->createMock(AttributeCode::class);
        $value = new TranslatableString([
            'en_GB' => '',
            'pl_PL' => null,
            'es_ES' => '1.0',
        ]);

        $result = $this->strategy->build(
            $id,
            $code,
            $value,
        );

        $this->assertEquals(
            new TranslatableStringValue(
                new TranslatableString([
                    'es_ES' => '1.0',
                ]),
            ),
            $result
        );
    }

    /**
     * @dataProvider nonNumericValueProvider
     */
    public function testShouldThrowExceptionOnNonNumericValue(string $value): void
    {
        $id = $this->createMock(AttributeId::class);
        $code = $this->createMock(AttributeCode::class);
        $value = new TranslatableString([
            'en_GB' => $value,
        ]);
        $this->expectException(ImportException::class);

        $this->strategy->build(
            $id,
            $code,
            $value,
        );
    }

    public function nonNumericValueProvider(): array
    {
        return [
            ['1.0f', '.', 'string', 'a12'],
        ];
    }
}
