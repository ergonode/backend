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
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Application\Form\Type\AttributeMapType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $languageQuery;

    /**
     * @var TreeQueryInterface
     */
    private TreeQueryInterface $categoryTreeQuery;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param LanguageQueryInterface  $languageQuery
     * @param TreeQueryInterface      $categoryTreeQuery
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        LanguageQueryInterface $languageQuery,
        TreeQueryInterface $categoryTreeQuery
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->languageQuery = $languageQuery;
        $this->categoryTreeQuery = $categoryTreeQuery;
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
        $languages = $this->languageQuery->getDictionaryActive();
        $categoryTrees = $this->categoryTreeQuery->getDictionary(new Language('en_GB'));

        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                ]
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
                ChoiceType::class,
                [
                    'label' => 'Default Language',
                    'property_path' => 'defaultLanguage',
                    'choices' => $languages,
                ]
            )
            ->add(
                'languages',
                ChoiceType::class,
                [
                    'label' => 'List of languages',
                    'choices' => $languages,
                    'multiple' => true,
                    'property_path' => 'languages',
                    'required' => false,
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
                'category_tree',
                ChoiceType::class,
                [
                    'label' => 'Category tree',
                    'property_path' => 'categoryTree',
                    'choices' => array_flip($categoryTrees),
                    'required' => false,
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
