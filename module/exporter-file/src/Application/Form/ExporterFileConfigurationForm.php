<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ergonode\ExporterFile\Infrastructure\Dictionary\WriterTypeDictionary;

/**
 */
class ExporterFileConfigurationForm extends AbstractType
{
    /**
     * @var WriterTypeDictionary
     */
    private WriterTypeDictionary $dictionary;

    /**
     * @param WriterTypeDictionary $dictionary
     */
    public function __construct(WriterTypeDictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = $this->dictionary->dictionary();

        $builder
            ->add(
                'name',
                TextType::class
            )->add(
                'format',
                ChoiceType::class,
                [
                    'choices' => $types,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'exporter',
                'data_class' => ExporterFileConfigurationModel::class,
                'allow_extra_fields' => true,
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
