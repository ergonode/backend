<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Form;

use Ergonode\Importer\Domain\Command\Source\CreateSourceCommand;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\ImporterMagento1\Application\Form\Type\LanguageMapType;
use Symfony\Component\Validator\Constraints\Length;

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
            ->add('test',
                TextType::class,
                [
                    'constraints' => new Length(['min' => 2])
                ]
            )
            ->add(
                'languages',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => LanguageMapType::class,
                ]
            );

        $builder ->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) {

            $data = $event->getData();

            if (!$data) {
                return;
            }

            $data = new CreateSourceCommand(
                SourceId::generate(),
                Magento1CsvSource::TYPE,
                ['test' => $data->test]
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
