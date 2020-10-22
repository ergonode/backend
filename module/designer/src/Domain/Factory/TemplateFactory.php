<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Factory;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;

class TemplateFactory
{
    /**
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param AttributeId|null  $defaultLabel
     * @param AttributeId|null  $defaultImage
     * @param array             $elements
     * @param MultimediaId|null $imageId
     *
     * @return Template
     *
     * @throws \Exception
     */
    public function create(
        TemplateId $id,
        TemplateGroupId $groupId,
        string $name,
        ?AttributeId $defaultLabel = null,
        ?AttributeId $defaultImage = null,
        array $elements = [],
        ?MultimediaId $imageId = null
    ): Template {
        Assert::allIsInstanceOf($elements, TemplateElement::class);

        $template = new Template(
            $id,
            $groupId,
            $name,
            $defaultLabel,
            $defaultImage,
            $imageId
        );

        foreach ($elements as $element) {
            $template->addElement($element);
        }

        return $template;
    }
}
