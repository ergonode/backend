<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\DateTimeTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformerTest extends TestCase
{
    private DateTimeTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new DateTimeTransformer();
    }

    /**
     * @dataProvider validReverseTransformCasesProvider
     */
    public function testShouldReverseTransform(string $dateTime, string $timezone): void
    {
        $result = $this->transformer->reverseTransform($dateTime);

        $this->assertEquals($timezone, $result->getTimezone()->getName());
    }

    public function testShouldReverseTransformEmpty(): void
    {
        $result = $this->transformer->reverseTransform(null);

        $this->assertNull($result);
    }

    /**
     * @return array
     */
    public function validReverseTransformCasesProvider(): array
    {
        return [
            ['2020-09-17T15:19:18Z', 'Z'],
            ['2020-09-17T15:19:18+01:00', '+01:00'],
            ['2020-09-17T15:19:18.015Z', 'Z'],
            ['2020-09-17T15:19:18.015-03:00', '-03:00'],
        ];
    }

    /**
     * @dataProvider invalidReverseTransformCasesProvider
     */
    public function testShouldThrowExceptionOnDenormalize(string $dateTime): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->reverseTransform($dateTime);
    }

    /**
     * @return string[][]
     */
    public function invalidReverseTransformCasesProvider(): array
    {
        return [
            ['2020-09-17 15:19:18'],
            ['2020-09-17'],
            ['2020-09-17T15:19:18'],
            ['2020-09-17T15:19:1812'],
            ['2020-09-17T15:19:18.01512321+01:00'],
        ];
    }

    public function testShouldTransform(): void
    {
        $date = new \DateTime();

        $result = $this->transformer->transform($date);

        $this->assertEquals($date->format(\DateTimeInterface::RFC3339), $result);
    }

    public function testShouldTransformEmpty(): void
    {
        $result = $this->transformer->transform(null);

        $this->assertNull($result);
    }

    public function testShouldThrowExceptionOnInvalidTypeTransform(): void
    {
        $this->expectException(TransformationFailedException::class);

        $this->transformer->transform($this);
    }
}
