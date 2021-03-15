<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Provider\ViewTemplateElementProvider;

class ViewTemplateBuilder
{
    private ViewTemplateElementProvider $provider;

    public function __construct(ViewTemplateElementProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function build(Template $template, Language $language): array
    {
        $elements = [];

        foreach ($template->getElements() as $element) {
            $elements[] = $this->provider->provide($element)->build($element, $language);
        }

        return [
            'id' => $template->getId(),
            'name' => $template->getName(),
            'elements' => $elements,
        ];
    }
}
