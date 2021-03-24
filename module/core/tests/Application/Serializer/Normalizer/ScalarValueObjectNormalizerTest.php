<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Serializer\Normalizer;

use Ergonode\Core\Application\Serializer\Normalizer\ScalarValueObjectNormalizer;
use Ergonode\Core\Tests\Application\Serializer\Normalizer\Fixtures\AbstractClass;
use Ergonode\Core\Tests\Application\Serializer\Normalizer\Fixtures\PrivateConstructorClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ScalarValueObjectNormalizerTest extends TestCase
{
    private ScalarValueObjectNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new ScalarValueObjectNormalizer();
    }

    /**
     * @dataProvider scalarValueObjectsProvider
     *
     * @param mixed $value
     */
    public function testShouldDenormalize(object $object, $value): void
    {
        $result = $this->normalizer->denormalize($value, get_class($object));

        $this->assertEquals($object, $result);
        $this->assertTrue($this->normalizer->supportsDenormalization($value, get_class($object)));
        $this->assertTrue($this->normalizer->supportsDenormalization([$value], get_class($object)));
    }

    /**
     * @dataProvider notSupportedDenormalizationProvider
     */
    public function testShouldNotSupportDenormalization(string $type): void
    {
        $this->assertFalse($this->normalizer->supportsDenormalization(null, $type));
    }

    public function notSupportedDenormalizationProvider(): array
    {
        $classes = array_map(
            fn(array $params) => [get_class($params[0])],
            $this->nonScalarValueObjectsProvider(),
        );

        return array_merge(
            $classes,
            [
                [\stdClass::class],
                ['string'],
                [AbstractClass::class],
            ],
        );
    }

    /**
     * @dataProvider nonScalarValueObjectsProvider
     *
     * @param mixed $data
     */
    public function testShouldThrowExceptionOnDenormalization(object $object): void
    {
        $this->expectException(ExceptionInterface::class);

        $this->normalizer->denormalize(1, get_class($object));
    }

    /**
     * @dataProvider scalarValueObjectsProvider
     *
     * @param mixed $expectedResult
     */
    public function testShouldNormalize(object $data, $expectedResult): void
    {
        $result = $this->normalizer->normalize($data);

        $this->assertEquals($expectedResult, $result);
        $this->assertTrue($this->normalizer->supportsNormalization($data));
    }

    /**
     * @dataProvider nonScalarValueObjectsProvider
     */
    public function testShouldNotSupportNormalization(object $data): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($data));
    }

    /**
     * @dataProvider nonScalarValueObjectsProvider
     *
     * @param mixed $data
     */
    public function testShouldThrowExceptionOnNormalization($data): void
    {
        $this->expectException(ExceptionInterface::class);

        $this->normalizer->normalize($data);
    }

    public function scalarValueObjectsProvider(): array
    {
        $string = new class('tst') {
            private string $a;
            public function __construct(string $a)
            {
                $this->a = $a;
            }
            public static function isValid(string $a): bool
            {
                return true;
            }
            public function getValue(): string
            {
                return $this->a;
            }
        };
        $int = new class(1) {
            private int $a;
            public function __construct(int $a)
            {
                $this->a = $a;
            }
            public static function isValid(int $a): bool
            {
                return true;
            }
            public function getValue(): int
            {
                return $this->a;
            }
        };
        $float = new class(1.1) {
            private float $a;
            public function __construct(float $a)
            {
                $this->a = $a;
            }
            public static function isValid(float $a): bool
            {
                return true;
            }
            public function getValue(): float
            {
                return $this->a;
            }
        };

        return [
            [$string, 'tst'],
            [$int, 1],
            [$float, 1.1],
        ];
    }

    public function nonScalarValueObjectsProvider(): array
    {
        $noMethods = new class() {
        };
        $missingIsValidAndGetValue = new class('') {
            public function __construct(string $a)
            {
            }
        };
        $missingGetValue = new class('') {
            public function __construct(string $a)
            {
            }
            public static function isValid(string $a): bool
            {
                return true;
            }
        };
        $missingIsValid = new class('') {
            public function __construct(string $a)
            {
            }
            public function getValue(): string
            {
                return '';
            }
        };
        $missingConstructor = new class() {
            public static function isValid(): bool
            {
                return true;
            }
            public function getValue(): string
            {
                return '';
            }
        };
        $mismatchedTypes = new class(1) {
            public function __construct(int $a)
            {
            }
            public static function isValid(): bool
            {
                return true;
            }
            public function getValue(): string
            {
                return '';
            }
        };
        $privateIsValid = new class('') {
            public function __construct(string $a)
            {
            }
            public function getValue(): string
            {
                return '';
            }
            // phpcs:ignore
            private static function isValid(): bool
            {
                return true;
            }
        };
        $privateGetValue = new class('') {
            public function __construct(string $a)
            {
            }
            public static function isValid(): bool
            {
                return true;
            }
            // phpcs:ignore
            private function getValue(): string
            {
                return '';
            }
        };
        $nonStaticIsValid = new class('') {
            public function __construct(string $a)
            {
            }
            public function isValid(): bool
            {
                return true;
            }
            public function getValue(): string
            {
                return '';
            }
        };
        $invalidIsValid = new class('') {
            public function __construct(string $a)
            {
            }
            public static function isValid(): string
            {
                return '';
            }
            public function getValue(): string
            {
                return '';
            }
        };

        return [
            [$noMethods],
            [$missingIsValidAndGetValue],
            [$missingGetValue],
            [$missingIsValid],
            [$missingConstructor],
            [$mismatchedTypes],
            [$privateIsValid],
            [$privateGetValue],
            [$nonStaticIsValid],
            [$invalidIsValid],
            [PrivateConstructorClass::create('')],
        ];
    }
}
