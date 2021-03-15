<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\Configuration\PriceAttributeConfigurationForm;
use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PriceAttributeForm extends AbstractType implements AttributeFormInterface
{
    public function supported(string $type): bool
    {
        return PriceAttribute::TYPE === $type;
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
                'label',
                TranslationType::class
            )
            ->add(
                'hint',
                TranslationType::class
            )
            ->add(
                'placeholder',
                TranslationType::class
            )
            ->add(
                'groups',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                ]
            )
            ->add(
                'scope',
                TextType::class,
            )
            ->add(
                'parameters',
                PriceAttributeConfigurationForm::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PriceAttributeFormModel::class,
            'empty_data' => new PriceAttributeFormModel(),
            'translation_domain' => 'attribute',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
