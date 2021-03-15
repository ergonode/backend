<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Attribute\Configuration;

use Ergonode\Attribute\Application\Model\Attribute\Property\PriceAttributePropertyModel;
use Ergonode\Core\Application\Form\Type\CurrencyFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceAttributeConfigurationForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'currency',
                CurrencyFormType::class,
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PriceAttributePropertyModel::class,
            'empty_data' => new PriceAttributePropertyModel(),
            'translation_domain' => 'attribute',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
