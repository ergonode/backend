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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Ergonode\ImporterMagento1\Application\Form\Type\StoreViewType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\ImporterMagento1\Application\Form\Type\LanguageMapType;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\ImporterMagento1\Application\Form\Type\AttributeMapType;

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
                'import',
                ChoiceType::class,
                [
                    'label' => 'Include in the imports',
                    'choices' => [
                        'Attributes' => 'attributes',
                        'Products' => 'products',
                        'Products images' => 'multimedia',
                        'Categories' => 'categories',
                        'Templates (Attribute set)' => 'templates',
                    ],
                    'multiple' => true,
                ]
            )
            ->add(
                'host',
                TextType::class,
                [
                    'constraints' => [
                        new Url(),
                    ],
                    'extra_fields_message' => 'Enter the address of the server where the product images are located',
                    'label' => 'Images host',
                ]
            )
            ->add(
                'mapping',
                StoreViewType::class,
                [
                    'label' => 'Store views',
                ]
            )
            ->add(
                'attributes',
                CollectionType::class,
                [
                    'label' => 'Attribute mapping',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => AttributeMapType::class,
                    'liform' => [
                        'format' => 'table',
                        'widget' => 'table',
                    ],
                ]
            );

        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) {

            /** @var ImporterMagento1ConfigurationModel $data */
            $data = $event->getData();

            if (!$data) {
                return;
            }

            $languages = [];
            foreach ($data->mapping->languages as $language) {
                $languages[$language->store] = $language->language->getCode();
            }
            $language = $data->mapping->defaultLanguage->getCode();
            $name = $data->name;
            $host = $data->host;
            $attributes = $data->attributes;

            $import = (array) $data->import;

            $data = new CreateSourceCommand(
                SourceId::generate(),
                Magento1CsvSource::TYPE,
                $name,
                [
                    'import' => $import,
                    'languages' => $languages,
                    'defaultLanguage' => $language,
                    'host' => $host,
                    'attributes' => $attributes,
                ]
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
            'label' => 'Import settings',
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
