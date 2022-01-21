<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\Form;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ergonode\ExporterFile\Infrastructure\Dictionary\WriterTypeDictionary;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExporterFileConfigurationForm extends AbstractType
{
    private WriterTypeDictionary $dictionary;

    private LanguageQueryInterface $query;

    private SegmentQueryInterface $segmentQuery;

    public function __construct(
        WriterTypeDictionary $dictionary,
        LanguageQueryInterface $query,
        SegmentQueryInterface $segmentQuery
    ) {
        $this->dictionary = $dictionary;
        $this->query = $query;
        $this->segmentQuery = $segmentQuery;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = $this->dictionary->dictionary();
        $languages = $this->query->getDictionaryActive();
        $exportType = array_combine(FileExportChannel::EXPORT_TYPES, FileExportChannel::EXPORT_TYPES);
        $segmentDictionary =  $this->segmentQuery->getDictionary();

        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                ]
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
                'export_type',
                ChoiceType::class,
                [
                    'label' => 'Export type',
                    'choices' => $exportType,
                    'property_path' => 'exportType',
                    'help' => 'Option "incremental" works only within the context of products. '
                        .'Once you edit or add new products only those changes will appear in the export.',
                ]
            )
            ->add(
                'format',
                ChoiceType::class,
                [
                    'label' => 'Format',
                    'choices' => $types,
                ]
            )
            ->add(
                'segmentId',
                ChoiceType::class,
                [
                    'label' => 'Segment',
                    'property_path' => 'segmentId',
                    'choices' => array_flip($segmentDictionary),
                    'required' => false,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'channel',
                'data_class' => ExporterFileConfigurationModel::class,
                'allow_extra_fields' => true,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
