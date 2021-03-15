<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Provider;

use Ergonode\Product\Application\Form\Product\ProductFormInterface;

class ProductFormProvider implements ProductSupportProviderInterface
{
    /**
     * @var ProductFormInterface[]
     */
    private array $forms;

    public function __construct(ProductFormInterface ...$forms)
    {
        $this->forms = $forms;
    }

    public function provide(string $type): string
    {
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return get_class($form);
            }
        }

        throw new \InvalidArgumentException('Unsupported product type');
    }

    public function supports(string $type): bool
    {
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return true;
            }
        }

        return false;
    }
}
