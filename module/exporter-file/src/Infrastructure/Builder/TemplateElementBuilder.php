<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\Designer\Domain\Entity\Template;

class TemplateElementBuilder
{
    /**
     * @var TemplateElementBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param TemplateElementBuilderInterface[] $builders
     */
    public function __construct(iterable $builders)
    {
        Assert::allIsInstanceOf($builders, TemplateElementBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(array_merge(['_name', '_type', '_x', '_y', '_width', '_height'], ...$result));
    }

    public function build(Template $template, TemplateElementInterface $element): ExportData
    {
        $data = new ExportData();

        $line = new ExportLineData();
        $line->set('_name', $template->getName());
        $line->set('_type', $element->getType());
        $line->set('_x', (string) $element->getPosition()->getX());
        $line->set('_y', (string) $element->getPosition()->getY());
        $line->set('_width', (string) $element->getSize()->getWidth());
        $line->set('_height', (string) $element->getSize()->getHeight());
        foreach ($this->builders as $builder) {
            $builder->build($element, $line);
        }
        $data->add($line);

        return $data;
    }
}
