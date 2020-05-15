<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Application\Form;

use Ergonode\Core\Application\Form\Type\LanguageType;
use Ergonode\Exporter\Domain\Command\ExportProfile\CreateExportProfileCommand;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ExporterMagento2ConfigurationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'filename',
                TextType::class
            )
            ->add(
                'default_language',
                LanguageType::class,
                [
                    'property_path' => 'defaultLanguage',
                ]
            );

        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) {
            /** @var ExporterMagento2CsvConfigurationModel $data */
            $data = $event->getData();

            if (!$data) {
                return;
            }

            $name = $data->name;
            $filename = $data->filename;
            $language = $data->defaultLanguage->getCode();
            /*
             * $data = new CreateExportProfileCommand(
             *     $name,
             *     Magento2ExportCsvProfile::TYPE,
             *     [
             *         'filename' => $filename,
             *         'defaultLanguage' => $language,
             *     ]
             * );
             * $event->setData($data);
            */
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'exporter',
                'data_class' => ExporterMagento2CsvConfigurationModel::class,
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
