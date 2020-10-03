<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Form\Type;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ImporterErgonode\Application\Model\Type\AttributeMapModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class AttributeMapType extends AbstractType
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;

    /**
     * @param AttributeQueryInterface $query
     */
    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dictionary = $this->query->getDictionary();

        $builder
            ->add(
                'code',
                TextType::class,
                [
                    'label' => 'Code',
                ]
            )
            ->add(
                'attribute',
                ChoiceType::class,
                [
                    'label' => 'Attribute',
                    'choices' => array_flip($dictionary),
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'import',
            'data_class' => AttributeMapModel::class,
        ]);
    }
}
