<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\LanguageTree;

use Ergonode\Core\Application\Model\LanguageTree\LanguageTreeUpdateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageTreeUpdateForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'languages',
                LanguageTreeNodeForm::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LanguageTreeUpdateFormModel::class,
            'translation_domain' => 'language_tree',
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
