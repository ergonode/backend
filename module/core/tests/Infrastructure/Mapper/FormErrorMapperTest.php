<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Mapper;

use Ergonode\Core\Infrastructure\Mapper\FormErrorMapper;
use Ergonode\Core\Infrastructure\Mapper\FormErrorMapperMessageProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 */
class FormErrorMapperTest extends TestCase
{
    /**
     * @var FormErrorMapperMessageProvider
     */
    private FormErrorMapperMessageProvider $provider;

    /**
     * @var FormInterface|MockObject
     */
    private $formElement;

    /**
     * @var FormErrorMapper
     */
    private FormErrorMapper $mapper;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var FormInterface|MockObject
     */
    private $errorFormElement;

    /**
     */
    protected function setUp(): void
    {
        $this->provider = $this->createMock(FormErrorMapperMessageProvider::class);
        $this->provider->method('getMessage')->willReturn('Very serious error');
        $this->formElement = $this->createMock(FormInterface::class);
        $this->formElement->method('isSubmitted')->willReturn(true);
        $this->errorFormElement = $this->createMock(FormInterface::class);
        $this->mapper = new FormErrorMapper($this->provider);
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getErrors')->willReturn(
            [
                $this->createMock(FormError::class),
            ]
        );
        $this->form->method('all')->willReturn(
            [
                $this->formElement,
            ]
        );
    }

    /**
     */
    public function testFormValidMapper(): void
    {
        $this->formElement->method('isValid')->willReturn(true);

        $result = $this->mapper->map($this->form);

        $this->assertEquals($result['form'][0], 'Very serious error');
    }

    /**
     * @param string $name
     * @param array  $expected
     *
     * @dataProvider dataProvider
     */
    public function testFormNotValidMapper(string $name, array $expected): void
    {
        $this->formElement->method('isValid')->willReturn(false);
        $this->formElement->method('getName')->willReturn($name);
        $this->formElement->method('getErrors')->willReturn(
            [
                $this->createMock(FormError::class),
            ]
        );
        $this->formElement->method('all')->willReturn(
            [
                $this->errorFormElement,
            ]
        );
        $this->errorFormElement->method('isSubmitted')->willReturn(false);
        $this->errorFormElement->method('isValid')->willReturn(false);
        $result = $this->mapper->map($this->form);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'name' => 'test',
                'expected' => [
                    'form' => [
                        'Very serious error',
                    ],
                    'test' =>
                        [
                            'Very serious error',
                        ],
                ],
            ],
            [
                'name' => '',
                'expected' => [
                    'form' => [
                        'Very serious error',
                    ],
                    0 => 'Very serious error',
                ],
            ],
            [
                'name' => '1',
                'expected' => [
                    'form' => [
                        'Very serious error',
                    ],
                    'element-1' => [
                        'Very serious error',
                    ],
                ],
            ],
        ];
    }
}
