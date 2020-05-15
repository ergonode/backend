<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Form;

use Ergonode\Exporter\Application\Form\DataTransformer\ExportProfileDataTransformer;
use Ergonode\Exporter\Domain\Query\ExportProfileQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ExportProfileType extends AbstractType
{
    /**
     * @var ExportProfileQueryInterface
     */
    private ExportProfileQueryInterface $query;

    /**
     * @param ExportProfileQueryInterface $query
     */
    public function __construct(ExportProfileQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ExportProfileDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $ids = $this->query->getAllExportProfileIds();
        $choices = array_combine($ids, $ids);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'Segment {{ value }} not exists',
                'multiple' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
