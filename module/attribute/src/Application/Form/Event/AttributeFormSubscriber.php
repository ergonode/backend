<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeDate\Application\Form\Type\DateFormatFormType;
use Ergonode\AttributeDate\Domain\Entity\DateAttribute;
use Ergonode\AttributePrice\Application\Form\Type\CurrencyFormType;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\AttributeUnit\Application\Form\Type\UnitFormType;
use Ergonode\AttributeUnit\Domain\Entity\UnitAttribute;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 */
class AttributeFormSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event): void
    {
        /** @var array $attribute */
        $form = $event->getForm()->get('parameters');
        $type = $this->provide($event, 'type');

        if ($type && UnitAttribute::TYPE === $type->getValue()) {
            $form->add(
                'unit',
                UnitFormType::class,
                [
                    'constraints' => [
                        new NotNull(),
                    ],
                ]
            );
        }

        if ($type && PriceAttribute::TYPE === $type->getValue()) {
            $form->add(
                'currency',
                CurrencyFormType::class,
                [
                    'invalid_message' => 'Invalid currency format',
                    'constraints' => [
                        new NotNull(),
                    ],
                ]
            );
        }

        if ($type && DateAttribute::TYPE === $type->getValue()) {
            $form->add(
                'format',
                DateFormatFormType::class,
                [
                    'constraints' => [
                        new NotNull(),
                    ],
                ]
            );
        }
    }

    /**
     * @param FormEvent $event
     * @param string    $field
     *
     * @return AttributeType
     */
    private function provide(FormEvent $event, string $field): ?AttributeType
    {
        if (\is_object($event->getData()) && $event->getData()->{$field}) {
            return $event->getData()->{$field};
        }

        if (\is_object($event->getForm()->getData()) && $event->getForm()->getData()->{$field}) {
            return $event->getForm()->getData()->{$field};
        }

        if (\is_array($event->getData()) && !empty($event->getData()[$field])) {
            return new AttributeType($event->getData()[$field]);
        }

        return null;
    }
}
