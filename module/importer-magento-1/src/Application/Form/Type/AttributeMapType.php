<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ImporterMagento1\Application\Model\Type\AttributeMapModel;

class AttributeMapType extends AbstractType
{
    private AttributeQueryInterface $query;

    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }


    /**
     * @param array $options
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'import',
            'data_class' => AttributeMapModel::class,
        ]);
    }
}
