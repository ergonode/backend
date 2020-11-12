<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Symfony\Component\HttpFoundation\Request;

class AssociatedProductAvailableChildrenGrid extends AbstractGrid
{
    private TemplateQueryInterface $templateQuery;

    /**
     * @var AbstractAttribute[]
     */
    private array $bindingAttributes;

    private AbstractAssociatedProduct $associatedProduct;

    private OptionQueryInterface $optionQuery;

    public function __construct(
        TemplateQueryInterface $templateQuery,
        OptionQueryInterface $optionQuery
    ) {
        $this->templateQuery = $templateQuery;
        $this->optionQuery = $optionQuery;
        $this->bindingAttributes = [];
    }

    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $templates = [];
        foreach ($this->templateQuery->getDictionary($language) as $key => $value) {
            $templates[] = new LabelFilterOption($key, $value);
        }
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()));
        $this->addColumn('template_id', new SelectColumn('template_id', 'Template', new MultiSelectFilter($templates)));
        $this->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()));
        $this->addColumn('default_image', new ImageColumn('default_image', 'Default image'));
        $attached = new BoolColumn('attached', 'Attached', new TextFilter());
        $attached->setEditable(true);
        $this->addColumn('attached', $attached);
        if ($this->bindingAttributes) {
            $this->addBindingColumn($language);
        }
        $this->addColumn('_links', new LinkColumn('hal', [
            'delete' => [
                'privilege' => 'PRODUCT_UPDATE',
                'route' => 'ergonode_product_child_remove',
                'parameters' => [
                    'language' => $language->getCode(),
                    'product' => $this->associatedProduct->getId(),
                    'child' => '{id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }

    /**
     * @param AbstractAttribute[] $bindingAttributes
     */
    public function addBindingAttributes(array $bindingAttributes): void
    {
        $this->bindingAttributes = $bindingAttributes;
    }

    public function addAssociatedProduct(AbstractAssociatedProduct $associatedProduct): void
    {
        $this->associatedProduct = $associatedProduct;
    }


    private function addBindingColumn(Language $language): void
    {
        foreach ($this->bindingAttributes as $bindingAttribute) {
            $options = $this->optionQuery->getAll($bindingAttribute->getId());
            $result = [];
            foreach ($options as $option) {
                $label = $option['label'][$language->getCode()] ?? null;
                $result[] = new FilterOption(
                    $option['id'],
                    $option['code'],
                    $label
                );
            }
            $attributeCode = $bindingAttribute->getCode()->getValue();
            $attributeLabel = $bindingAttribute->getLabel()->get($language);
            $this->addColumn(
                $attributeCode,
                new SelectColumn($attributeCode, $attributeLabel, new MultiSelectFilter($result))
            );
        }
    }
}
