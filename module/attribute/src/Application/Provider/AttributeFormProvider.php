<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Provider;

use Ergonode\Attribute\Application\Form\Attribute\AttributeFormInterface;

class AttributeFormProvider
{
    /**
     * @var AttributeFormInterface[]
     */
    private array $forms;

    public function __construct(AttributeFormInterface ...$forms)
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

        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
