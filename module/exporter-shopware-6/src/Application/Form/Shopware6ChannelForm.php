<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Form;

use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Application\Form\Type\CustomFieldAttributeMapType;
use Ergonode\ExporterShopware6\Application\Form\Type\PropertyGroupAttributeMapType;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Shopware6ChannelForm extends AbstractType
{
    private AttributeQueryInterface $attributeQuery;

    private LanguageQueryInterface $languageQuery;

    private TreeQueryInterface $categoryTreeQuery;

    private SegmentQueryInterface $segmentQuery;

    private ProductCollectionQueryInterface $productCollectionQuery;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        LanguageQueryInterface $languageQuery,
        TreeQueryInterface $categoryTreeQuery,
        SegmentQueryInterface $segmentQuery,
        ProductCollectionQueryInterface $productCollectionQuery
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->languageQuery = $languageQuery;
        $this->categoryTreeQuery = $categoryTreeQuery;
        $this->segmentQuery = $segmentQuery;
        $this->productCollectionQuery = $productCollectionQuery;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $priceAttributeDictionary = $this->attributeQuery->getDictionary([PriceAttribute::TYPE]);
        $textAttributeDictionary = $this->attributeQuery->getDictionary([TextAttribute::TYPE]);
        $textareaAttributeDictionary = $this->attributeQuery->getDictionary([TextareaAttribute::TYPE]);
        $numericAttributeDictionary = $this->attributeQuery->getDictionary([NumericAttribute::TYPE]);
        $galleryAttributeDictionary = $this->attributeQuery->getDictionary([GalleryAttribute::TYPE]);
        $languages = $this->languageQuery->getDictionaryActive();
        $categoryTrees = array_merge(['' => ''], $this->categoryTreeQuery->getDictionary(new Language('en_GB')));
        $segmentDictionary = array_merge(['' => ''], $this->segmentQuery->getDictionary());
        $productCollectionDictionary = $this->productCollectionQuery->getDictionary();

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
                'segment',
                ChoiceType::class,
                [
                    'label' => 'Segment',
                    'property_path' => 'segment',
                    'choices' => array_flip($segmentDictionary),
                    'required' => false,
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
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Name',
                    'choices' => array_flip($textAttributeDictionary),
                    'property_path' => 'attributeProductName',
                ]
            )
            ->add(
                'attribute_product_active',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Active',
                    'choices' => array_flip($numericAttributeDictionary),
                    'property_path' => 'attributeProductActive',
                ]
            )
            ->add(
                'attribute_product_stock',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Stock',
                    'choices' => array_flip($numericAttributeDictionary),
                    'property_path' => 'attributeProductStock',
                ]
            )
            ->add(
                'attribute_product_price_gross',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Price Gross',
                    'choices' => array_flip($priceAttributeDictionary),
                    'property_path' => 'attributeProductPriceGross',
                ]
            )
            ->add(
                'attribute_product_price_net',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Price Net',
                    'choices' => array_flip($priceAttributeDictionary),
                    'property_path' => 'attributeProductPriceNet',
                ]
            )
            ->add(
                'attribute_product_tax',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Tax',
                    'choices' => array_flip($numericAttributeDictionary),
                    'property_path' => 'attributeProductTax',
                ]
            )
            ->add(
                'attribute_product_description',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Description',
                    'choices' => array_flip($textareaAttributeDictionary),
                    'property_path' => 'attributeProductDescription',
                    'required' => false,
                ]
            )
            ->add(
                'attribute_product_gallery',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Gallery',
                    'choices' => array_flip($galleryAttributeDictionary),
                    'property_path' => 'attributeProductGallery',
                    'required' => false,
                ]
            )
            ->add(
                'attribute_product_meta_title',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Meta Title',
                    'help' => 'Value in product should contain 255 characters or less.',
                    'choices' => array_flip($textareaAttributeDictionary),
                    'property_path' => 'attributeProductMetaTitle',
                    'required' => false,
                ]
            )
            ->add(
                'attribute_product_meta_description',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Meta Description',
                    'help' => 'Value in product should contain 255 characters or less.',
                    'choices' => array_flip($textareaAttributeDictionary),
                    'property_path' => 'attributeProductMetaDescription',
                    'required' => false,
                ]
            )
            ->add(
                'attribute_product_keywords',
                ChoiceType::class,
                [
                    'label' => 'Attribute Product Keywords',
                    'choices' => array_flip($textareaAttributeDictionary),
                    'property_path' => 'attributeProductKeywords',
                    'required' => false,
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
                    'entry_type' => PropertyGroupAttributeMapType::class,
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
                    'entry_type' => CustomFieldAttributeMapType::class,
                    'required' => false,
                ]
            )
            ->add(
                'cross_selling',
                ChoiceType::class,
                [
                    'label' => 'List of Product Collections',
                    'choices' => array_flip($productCollectionDictionary),
                    'multiple' => true,
                    'property_path' => 'crossSelling',
                    'required' => false,
                ]
            );
    }

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

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
