<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Provider;

use Ergonode\Category\Application\Form\CategoryFormInterface;

class CategoryFormProvider
{
    /**
     * @var CategoryFormInterface[]
     */
    private array $forms;

    /**
     * @param array|CategoryFormInterface ...$forms
     */
    public function __construct(CategoryFormInterface ...$forms)
    {
        $this->forms = $forms;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function provide(string $type): string
    {
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return get_class($form);
            }
        }

        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
