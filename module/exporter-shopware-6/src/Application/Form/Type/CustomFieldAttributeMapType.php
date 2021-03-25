<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Form\Type;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterShopware6\Application\Model\Type\CustomFieldAttributeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomFieldAttributeMapType extends AbstractType
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
                'id',
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
            'translation_domain' => 'exporter',
            'data_class' => CustomFieldAttributeModel::class,
        ]);
    }
}
