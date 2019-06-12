<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Form;

use Ergonode\Importer\Application\Model\Form\UploadModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class UploadForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('upload', FileType::class);
        $builder->add('reader', TextType::class);
        $builder->add('transformer', TextType::class);
        $builder->add('action', TextType::class);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix() :?string
    {
        return null;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UploadModel::class,
            'translation_domain' => 'upload',
        ]);
    }
}
