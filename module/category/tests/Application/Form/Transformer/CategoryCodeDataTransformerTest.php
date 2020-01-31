<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Category\Application\Form\Transformer;

use Ergonode\Category\Application\Form\Transformer\CategoryCodeDataTransformer;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryCodeDataTransformerTest extends TestCase
{

    /**
     * @var CategoryCodeDataTransformer
     */
    protected CategoryCodeDataTransformer $transformer;

    /**
     */
    protected function setUp()
    {
        $this->transformer = new CategoryCodeDataTransformer();
    }

    /**
     * @param CategoryCode|null $categoryCodeValueObject
     * @param string|null       $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?CategoryCode $categoryCodeValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($categoryCodeValueObject));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid CategoryCode object
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param CategoryCode|null $categoryCodeValueObject
     * @param string|null       $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?CategoryCode $categoryCodeValueObject, ?string $string): void
    {
        $this->assertEquals($categoryCodeValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid category code
     * CS2ZiKK4TzJNNmZReVBFYwPZg2zUOL3RLv7L2VgG6nDnz8enH8nGy4iz1yQZuppKDAHfHUVEHpZZ7Ca0Tu4wZHwrpqNKdEw6bNRSulWLxHpEODnb
     * WH9iosh0e0AxYkzA2EFPmPm0faRUq5ae9EeQ5IpgUxFxFmwzpOGm5DJhR0gczdEdL0KxJmYzWY0fV34H8QzcCAt3nAfAHWStwVhWNv2L2GcLMjUT
     * XEwTODyi0XMk4ZBFcaIk9S3igHo6C2cg9IVQ value
     */
    public function testReverseTransformException(): void
    {
        $value = 'CS2ZiKK4TzJNNmZReVBFYwPZg2zUOL3RLv7L2VgG6nDnz8enH8nGy4iz1yQZuppKDAHfHUVEHpZZ7Ca0Tu4wZHwrpqNKdEw6bN'.
            'RSulWLxHpEODnbWH9iosh0e0AxYkzA2EFPmPm0faRUq5ae9EeQ5IpgUxFxFmwzpOGm5DJhR0gczdEdL0KxJmYzWY0fV34H8QzcCAt3nA'.
            'fAHWStwVhWNv2L2GcLMjUTXEwTODyi0XMk4ZBFcaIk9S3igHo6C2cg9IVQ';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'categoryCodeValueObject' => new CategoryCode('boots'),
                'string' => 'boots',
            ],
            [
                'categoryCodeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
