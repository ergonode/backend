<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class AttributeOptionType extends AbstractType implements EventSubscriberInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'key',
                TextType::class
            )
            ->addEventSubscriber($this);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributeOptionModel::class,
            'translation_domain' => 'attribute',
        ]);
    }

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
        $data = $event->getData();
        $value = isset($data['value']) ? $data['value'] : null;
        /** @var array $attribute */
        $form = $event->getForm();

        if (is_array($value)) {
            $form->add(
                'value',
                TranslationType::class
            );
        } else {
            $form->add(
                'value',
                TextType::class
            );
        }
    }
}
