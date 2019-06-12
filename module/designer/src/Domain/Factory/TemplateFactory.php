<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Factory;

use Ergonode\Designer\Domain\Entity\AbstractTemplateElement;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Webmozart\Assert\Assert;

/**
 */
class TemplateFactory
{
    /**
     * @param TemplateId                $id
     * @param TemplateGroupId           $groupId
     * @param string                    $name
     * @param AbstractTemplateElement[] $elements
     * @param string[]                  $sections
     * @param MultimediaId|null         $imageId
     *
     * @return Template
     */
    public function create(
        TemplateId $id,
        TemplateGroupId $groupId,
        string $name,
        array $elements = [],
        array $sections = [],
        ?MultimediaId $imageId = null
    ): Template {
        Assert::allIsInstanceOf($elements, AbstractTemplateElement::class);

        $template = new Template(
            $id,
            $groupId,
            $name,
            $imageId
        );

        foreach ($elements as $element) {
            $template->addElement($element);
        }

        foreach ($sections as $column => $section) {
            $template->addSection($column, $section);
        }

        return $template;
    }
}
