<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchActionValueTranslationType extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'language',
                TextType::class
            )
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();
                if (array_key_exists('value', $data) && is_array($data['value'])) {
                    $form->add(
                        'value',
                        CollectionType::class,
                        [
                            'allow_add' => true,
                            'allow_delete' => true,
                            'entry_type' => TextType::class,
                        ]
                    );
                } else {
                    $form->add(
                        'value',
                        TextType::class,
                    );
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'batch-action',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
