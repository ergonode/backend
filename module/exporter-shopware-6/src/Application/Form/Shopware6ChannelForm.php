<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Form;

use Ergonode\Attribute\Application\Form\Type\AttributeIdType;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterShopware6\Application\Form\Type\AttributeMapType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;

/**
 */
class Shopware6ChannelForm extends AbstractType
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @param AttributeQueryInterface $attributeQuery
     */
    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $attributeDictionary = $this->attributeQuery->getDictionary();
        $priceAttributeDictionary = $this->attributeQuery->getDictionary([PriceAttribute::TYPE]);
        $textAttributeDictionary = $this->attributeQuery->getDictionary([TextAttribute::TYPE]);
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'host',
                TextType::class,
                [
                    'help' => 'Enter the host API address',
                    'label' => 'API host',
                ]
            )
            ->add(
                'client_id',
                TextType::class,
                [
                    'label' => 'Access key ID',
                    'property_path' => 'clientId',
                ]
            )
            ->add(
                'client_key',
                TextType::class,
                [
                    'label' => 'Secret access key',
                    'property_path' => 'clientKey',
                ]
            )
            ->add(
                'default_language',
                TextType::class,
                [
                    'property_path' => 'defaultLanguage',
                ]
            )
            ->add(
                'languages',
                CollectionType::class,
                [
                    'label' => 'List of languages',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                    'property_path' => 'languages',
                ]
            )
            ->add(
                'attribute_product_name',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Name',
                    'choices' => array_flip($textAttributeDictionary),
                    'property_path' => 'attributeProductName',
                ]
            )
            ->add(
                'attribute_product_active',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Active',
                    'choices' => array_flip($attributeDictionary),
                    'property_path' => 'attributeProductActive',
                ]
            )
            ->add(
                'attribute_product_stock',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Stock',
                    'choices' => array_flip($attributeDictionary),
                    'property_path' => 'attributeProductStock',
                ]
            )
            ->add(
                'attribute_product_price_gross',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Price Gross',
                    'choices' => array_flip($priceAttributeDictionary),
                    'property_path' => 'attributeProductPriceGross',
                ]
            )
            ->add(
                'attribute_product_price_net',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Price Net',
                    'choices' => array_flip($priceAttributeDictionary),
                    'property_path' => 'attributeProductPriceNet',
                ]
            )
            ->add(
                'attribute_product_tax',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Tax',
                    'choices' => array_flip($attributeDictionary),
                    'property_path' => 'attributeProductTax',
                ]
            )
            ->add(
                'attribute_product_description',
                AttributeIdType::class,
                [
                    'label' => 'Attribute Product Description',
                    'choices' => array_flip($attributeDictionary),
                    'property_path' => 'attributeProductDescription',
                ]
            )
            ->add(
                'property_group',
                CollectionType::class,
                [
                    'property_path' => 'propertyGroup',
                    'label' => 'List Property Group to Export',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => AttributeMapType::class,
                    'required' => false,
                ]
            )
            ->add(
                'custom_field',
                CollectionType::class,
                [
                    'property_path' => 'customField',
                    'label' => 'List custom field to export',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => AttributeMapType::class,
                    'required' => false,
                ]
            )
            ->add(
                'category_tree',
                TextType::class,
                [
                    'property_path' => 'categoryTree',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'exporter',
                'data_class' => Shopware6ChannelFormModel::class,
                'allow_extra_fields' => true,
                'label' => 'Export settings',
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
