<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Provider;

use Ergonode\Product\Application\Form\Product\AbstractProductForm;

/**
 */
class ProductFormProvider
{
    /**
     * @var AbstractProductForm[]
     */
    private array $forms;

    /**
     * @param AbstractProductForm ...$forms
     */
    public function __construct(AbstractProductForm ...$forms)
    {
        $this->forms = $forms;
    }

    /**
     * @param string $type
     *
     * @return ?string
     */
    public function provide(string $type): ?string
    {
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return get_class($form);
            }
        }

        return null;
    }
}
