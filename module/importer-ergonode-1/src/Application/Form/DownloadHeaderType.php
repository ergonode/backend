<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\ImporterErgonode1\Application\Model\DownloadHeaderModel;

class DownloadHeaderType extends AbstractType
{

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'key',
                TextType::class,
                [
                    'label' => 'Key',
                ]
            )
            ->add(
                'value',
                TextType::class,
                [
                    'label' => 'Value',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'import',
            'data_class' => DownloadHeaderModel::class,
        ]);
    }
}
