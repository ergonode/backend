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
    private $provider;

    /**
     * @var FormInterface|MockObject
     */
    private $formElement;

    /**
     * @var FormErrorMapper
     */
    private $mapper;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var FormInterface|MockObject
     */
    private $errorFormElement;

    protected function setUp()
    {
        $this->provider = $this->createMock(FormErrorMapperMessageProvider::class);
        $this->provider->expects($this->any())->method('getMessage')->willReturn('Very serious error');
        $this->formElement = $this->createMock(FormInterface::class);
        $this->formElement->expects($this->any())->method('isSubmitted')->willReturn(true);
        $this->errorFormElement = $this->createMock(FormInterface::class);
        $this->mapper = new FormErrorMapper($this->provider);
        $this->form = $this->createMock(FormInterface::class);
        $this->form->expects($this->any())->method('getErrors')->willReturn(
            [
                $this->createMock(FormError::class),
            ]
        );
        $this->form->expects($this->any())->method('all')->willReturn(
            [
                $this->formElement,
            ]
        );
    }

    /**
     */
    public function testFormValidMapper()
    {
        $this->formElement->expects($this->any())->method('isValid')->willReturn(true);

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
        $this->formElement->expects($this->any())->method('isValid')->willReturn(false);
        $this->formElement->expects($this->any())->method('getName')->willReturn($name);
        $this->formElement->expects($this->any())->method('getErrors')->willReturn(
            [
                $this->createMock(FormError::class),
            ]
        );
        $this->formElement->expects($this->any())->method('all')->willReturn(
            [
                $this->errorFormElement,
            ]
        );
        $this->errorFormElement->expects($this->any())->method('isSubmitted')->willReturn(false);
        $this->errorFormElement->expects($this->any())->method('isValid')->willReturn(false);
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
                    'form' =>
                        [
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
                    'form' =>
                        [
                            'Very serious error',
                        ],
                    0 => 'Very serious error',
                ],
            ],
            [
                'name' => '1',
                'expected' => [
                    'form' =>
                        [
                            'Very serious error',
                        ],
                    'element-1' =>
                        [
                            'Very serious error',
                        ],
                ],
            ],
        ];
    }
}
