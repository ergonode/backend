<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Provider;

use Ergonode\Product\Application\Form\Product\ProductFormInterface;

/**
 */
class ProductFormProvider
{
    /**
     * @var ProductFormInterface[]
     */
    private array $forms;

    /**
     * @param array|ProductFormInterface ...$forms
     */
    public function __construct(ProductFormInterface ...$forms)
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
