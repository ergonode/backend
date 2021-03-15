<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Tests\Infrastructure\Faker;

use Ergonode\Fixture\Infrastructure\Faker\LanguageFaker;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;

class LanguageFakerTest extends TestCase
{
    public function testRandomValue(): void
    {
        /** @var Generator|MockObject $generator */
        $generator = $this->createMock(Generator::class);
        $faker = new LanguageFaker($generator);
        $result = $faker->language();

        self::assertInstanceOf(Language::class, $result);
    }

    public function testCustomValue(): void
    {
        /** @var Generator|MockObject $generator */
        $generator = $this->createMock(Generator::class);
        $faker = new LanguageFaker($generator);
        $result = $faker->language('pl_PL');

        self::assertEquals($result->getCode(), 'pl_PL');
    }
}
