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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

/**
 */
class ExporterFileConfigurationForm extends AbstractType
{
    /**
     * @var WriterTypeDictionary
     */
    private WriterTypeDictionary $dictionary;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @param WriterTypeDictionary   $dictionary
     * @param LanguageQueryInterface $query
     */
    public function __construct(WriterTypeDictionary $dictionary, LanguageQueryInterface $query)
    {
        $this->dictionary = $dictionary;
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = $this->dictionary->dictionary();
        $languages = $this->query->getDictionaryActive();

        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'languages',
                ChoiceType::class,
                [
                    'label' => 'Languages',
                    'choices' => $languages,
                    'multiple' => true,
                ]
            )
            ->add(
                'format',
                ChoiceType::class,
                [
                    'label' => 'Format',
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
