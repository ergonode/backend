<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Workflow;

use Ergonode\Workflow\Application\Form\TransitionForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ergonode\Workflow\Domain\Entity\Workflow;

class WorkflowForm extends AbstractType implements WorkflowFormInterface
{
    public function supported(string $type): bool
    {
        return Workflow::TYPE === $type;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'code',
                TextType::class
            )
            ->add(
                'statuses',
                CollectionType::class,
                [
                    'label' => 'Statuses',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                ]
            )
            ->add(
                'transitions',
                CollectionType::class,
                [
                    'label' => 'Transitions',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TransitionForm::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkflowFormModel::class,
            'translation_domain' => 'workflow',
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
