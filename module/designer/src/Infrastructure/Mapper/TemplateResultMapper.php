<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Mapper;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateResultMapper
{
    private AttributeRepositoryInterface $repository;

    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return array[]
     */
    public function map(Template $template): array
    {
        $result['id'] = $template->getId()->getValue();
        $result['name'] = $template->getName();
        $result['image_id'] = $template->getImageId() ? $template->getImageId()->getValue() : null;
        $result['default_label'] = $template->getDefaultLabel() ? $template->getDefaultLabel()->getValue() : null;
        $result['default_image'] = $template->getDefaultImage() ? $template->getDefaultImage()->getValue() : null;
        $result['group_id'] = $template->getGroupId() ? $template->getGroupId()->getValue() : null;
        foreach ($template->getElements() as $element) {
            $result['elements'][] = $this->getElement($element);
        }

        return $result;
    }

    private function getElement(TemplateElementInterface $element): array
    {
        $result['position']['x'] = $element->getPosition()->getX();
        $result['position']['y'] = $element->getPosition()->getY();
        $result['size']['width'] = $element->getSize()->getWidth();
        $result['size']['height'] = $element->getSize()->getHeight();
        $result['properties']['type'] = $element->getType();
        if ($element instanceof AttributeTemplateElement) {
            $attribute = $this->repository->load($element->getAttributeId());
            Assert::notNull($attribute);
            $result['type'] = $attribute->getType();
            $result['properties']['required'] = $element->isRequired();
            $result['properties']['attribute_id'] = $element->getAttributeId();
        } elseif ($element instanceof UiTemplateElement) {
            $result['type'] = 'SECTION';
            $result['properties']['label'] = $element->getLabel();
        }

        return $result;
    }
}
