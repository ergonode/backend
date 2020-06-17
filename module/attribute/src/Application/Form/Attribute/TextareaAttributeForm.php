<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\Configuration\TextareaAttributeConfigurationForm;
use Ergonode\Attribute\Application\Form\Type\AttributeCodeType;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Application\Model\Attribute\TextareaAttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class TextareaAttributeForm extends AbstractType implements AttributeFormInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return TextareaAttribute::TYPE === $type;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'code',
                AttributeCodeType::class
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
                AttributeGroupType::class
            )
            ->add(
                'scope',
                TextType::class,
            )
            ->add(
                'parameters',
                TextareaAttributeConfigurationForm::class
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TextareaAttributeFormModel::class,
            'empty_data' => new TextareaAttributeFormModel(),
            'translation_domain' => 'attribute',
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
