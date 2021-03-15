<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\Form;

use Ergonode\ImporterErgonode1\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImporterErgonodeConfigurationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'name',
                ]
            )
            ->add(
                'import',
                ChoiceType::class,
                [
                    'label' => 'Include in the imports',
                    'choices' => [
                        'Attributes' => ErgonodeZipSource::ATTRIBUTES,
                        'Categories' => ErgonodeZipSource::CATEGORIES,
                        'Options' => ErgonodeZipSource::OPTIONS,
                        'Products' => ErgonodeZipSource::PRODUCTS,
                        'Templates' => ErgonodeZipSource::TEMPLATES,
                    ],
                    'multiple' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'importer',
            'data_class' => ImporterErgonodeConfigurationModel::class,
            'allow_extra_fields' => true,
            'label' => 'Import settings',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
