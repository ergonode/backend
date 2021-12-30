<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Provider;

use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Application\Form\BatchActionReprocessFormInterface;

class BatchActionReprocessFormProvider
{
    /**
     * @var BatchActionReprocessFormInterface[]
     */
    private iterable $forms;

    /**
     * @param BatchActionReprocessFormInterface[] $forms
     */
    public function __construct(iterable $forms)
    {
        Assert::allIsInstanceOf($forms, BatchActionReprocessFormInterface::class);

        $this->forms = $forms;
    }

    public function provide(string $type): string
    {
        $type = strtolower(trim($type));
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return get_class($form);
            }
        }

        throw new \RuntimeException(sprintf('Can\' find Batch action form for %s type', $type));
    }
}
