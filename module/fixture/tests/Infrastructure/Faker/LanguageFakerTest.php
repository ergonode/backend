<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Tests\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Fixture\Infrastructure\Faker\LanguageFaker;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageFakerTest extends TestCase
{
    /**
     */
    public function testRandomValue(): void
    {
        /** @var Generator|MockObject $generator */
        $generator = $this->createMock(Generator::class);
        $faker = new LanguageFaker($generator);
        $result = $faker->language();

        $this->assertContains($result->getCode(), Language::AVAILABLE);
    }

    /**
     */
    public function testCustomValue(): void
    {
        /** @var Generator|MockObject $generator */
        $generator = $this->createMock(Generator::class);
        $faker = new LanguageFaker($generator);
        $result = $faker->language(Language::PL);

        $this->assertContains($result->getCode(), [Language::PL]);
    }
}
