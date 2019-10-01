<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Event;

use Ergonode\Attribute\Application\Form\Event\AttributeFormSubscriber;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeDate\Application\Form\Type\DateFormatFormType;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Ergonode\AttributePrice\Application\Form\Type\CurrencyFormType;
use Ergonode\AttributeUnit\Application\Form\Type\UnitFormType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 */
class AttributeFormSubscriberTest extends TestCase
{
    /**
     * @var FormEvent|MockObject
     */
    private $event;

    protected function setUp()
    {
        $this->event = $this->createMock(FormEvent::class);
    }

    /**
     *
     */
    public function testOnPreSubmitUnit()
    {
        $form = $this->createMock(FormInterface::class);
        $formParameters = $this->createMock(FormInterface::class);
        $form->expects($this->any())->method('get')->willReturn($formParameters);
        $this->event->expects($this->any())->method('getForm')->willReturn($form);
        $this->event->expects($this->any())->method('getData')->willReturn(['type' => 'UNIT']);
        $formParameters->expects($this->once())->method('add')->with(
            'unit',
            UnitFormType::class,
            [
                'constraints' => [
                    new NotNull(),
                ],
            ]
        );
        $subscriber = new AttributeFormSubscriber($this->event);
        $subscriber->onPreSubmit($this->event);
    }

    /**
     *
     */
    public function testOnPreSubmitCurrency()
    {
        $form = $this->createMock(FormInterface::class);
        $formParameters = $this->createMock(FormInterface::class);
        $form->expects($this->any())->method('get')->willReturn($formParameters);
        $this->event->expects($this->any())->method('getForm')->willReturn($form);
        $data = $this->createMock(AttributeType::class);
        $data->type = new AttributeType('PRICE');
        $this->event->expects($this->any())->method('getData')->willReturn($data);
        $formParameters->expects($this->once())->method('add')->with(
            'currency',
            CurrencyFormType::class,
            [
                'invalid_message' => 'Invalid currency format',
                'constraints' => [
                    new NotNull(),
                ],
            ]
        );
        $subscriber = new AttributeFormSubscriber($this->event);
        $subscriber->onPreSubmit($this->event);
    }

    /**
     *
     */
    public function testOnPreSubmitFormat()
    {
        $form = $this->createMock(FormInterface::class);
        $formParameters = $this->createMock(FormInterface::class);
        $form->expects($this->any())->method('get')->willReturn($formParameters);
        $form1 = $this->createMock(FormInterface::class);
        $form1->type = new AttributeType('DATE');
        $form->expects($this->any())->method('getData')->willReturn($form1);
        $this->event->expects($this->any())->method('getForm')->willReturn($form);
        $formParameters->expects($this->once())->method('add')->with(
            'format',
            DateFormatFormType::class,
            [
                'constraints' => [
                    new NotNull(),
                ],
            ]
        );
        $subscriber = new AttributeFormSubscriber($this->event);
        $subscriber->onPreSubmit($this->event);
    }

    /**
     *
     */
    public function testOnPreSubmitImage()
    {
        $form = $this->createMock(FormInterface::class);
        $formParameters = $this->createMock(FormInterface::class);
        $form->expects($this->any())->method('get')->willReturn($formParameters);
        $this->event->expects($this->any())->method('getForm')->willReturn($form);
        $this->event->expects($this->any())->method('getData')->willReturn(['type' => 'IMAGE']);
        $formParameters->expects($this->once())->method('add')->with(
            'formats',
            ChoiceType::class,
            [
                'choices' => ImageFormat::AVAILABLE,
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'Unsupported image format, accept formats: <formats>',
                'invalid_message_parameters' => ['<formats>' => implode(', ', ImageFormat::AVAILABLE)],
                'constraints' => [
                    new NotBlank(['message' => 'At least one image format is required']),
                ],
            ]
        );
        $subscriber = new AttributeFormSubscriber($this->event);
        $subscriber->onPreSubmit($this->event);
    }


}
