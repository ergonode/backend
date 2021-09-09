<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Factory;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\ValueObject\TemplateCode;

class TemplateFactory
{
    /**
     * @param TemplateElementInterface[] $elements
     */
    public function create(
        TemplateId $id,
        TemplateCode $code,
        TemplateGroupId $groupId,
        string $name,
        ?AttributeId $defaultLabel = null,
        ?AttributeId $defaultImage = null,
        array $elements = [],
        ?MultimediaId $imageId = null
    ): Template {
        Assert::allIsInstanceOf($elements, TemplateElementInterface::class);

        $template = new Template(
            $id,
            $code,
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
