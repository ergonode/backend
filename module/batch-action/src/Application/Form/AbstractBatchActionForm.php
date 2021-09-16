<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFormModel;
use Ergonode\BatchAction\Application\Form\Type\BatchActionFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

abstract class AbstractBatchActionForm extends AbstractType implements BatchActionFormInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                TextType::class
            )
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();
                if (is_string($data['filter'] ?? null)) {
                    $form->add(
                        'filter',
                        TextType::class,
                    );
                } else {
                    $form->add(
                        'filter',
                        BatchActionFilterType::class,
                    );
                }
            })
            ->add(
                'autoEndOnErrors',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!array_key_exists('autoEndOnErrors', $data)) {
                    $data['autoEndOnErrors'] = true;
                    $event->setData($data);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => BatchActionFormModel::class,
                'translation_domain' => 'batch-action',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
