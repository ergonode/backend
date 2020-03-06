<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Form;

use Ergonode\Importer\Domain\Command\Source\CreateSourceCommand;
use Ergonode\ImporterMagento1\Application\Form\Type\ImportStepType;
use Ergonode\ImporterMagento1\Application\Model\ImporterMagento1ConfigurationModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\ImporterMagento1\Application\Form\Type\LanguageMapType;
use Ergonode\Core\Application\Form\Type\LanguageType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

/**
 */
class ImporterMagento1ConfigurationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'host',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Url(),
                    ],
                ]
            )
            ->add(
                'default_language',
                LanguageType::class,
                [
                    'property_path' => 'defaultLanguage',
                ]
            )
            ->add(
                'languages',
                CollectionType::class,
                [
                    'label' => 'Mapped Languages',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => LanguageMapType::class,
                    'liform' => [
                        'format' => 'table',
                        'widget' => 'table',
                    ],
                ]
            )
            ->add(
                'import',
                ImportStepType::class
            );

        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) {

            /** @var ImporterMagento1ConfigurationModel $data */
            $data = $event->getData();

            if (!$data) {
                return;
            }

            $languages = [];
            foreach ($data->languages as $language) {
                $languages[$language->store] = $language->language->getCode();
            }
            $language = $data->defaultLanguage->getCode();
            $name = $data->name;
            $host = $data->host;

            $import = (array) $data->import;

            $data = new CreateSourceCommand(
                SourceId::generate(),
                Magento1CsvSource::TYPE,
                $name,
                ['import' => $import, 'languages' => $languages, 'defaultLanguage' => $language, 'host' => $host]
            );

            $event->setData($data);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'importer',
            'data_class' => ImporterMagento1ConfigurationModel::class,
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
