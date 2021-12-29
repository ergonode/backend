<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Ergonode\Attribute\Application\Form\Model\Option\OptionMoveModel;

class OptionMoveForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'positionId',
                TextType::class,
            )
            ->add(
                'after',
                CheckboxType::class
            )->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!array_key_exists('after', $data)) {
                    $data['after'] = true;
                    $event->setData($data);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OptionMoveModel::class,
            'translation_domain' => 'attribute',
            'allow_extra_fields' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
