<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Provider;

use Ergonode\BatchAction\Application\Form\BatchActionFormInterface;
use Webmozart\Assert\Assert;

class BatchActionFormProvider
{
    /**
     * @var BatchActionFormInterface[]
     */
    private iterable $forms;

    /**
     * @param BatchActionFormInterface[] $forms
     */
    public function __construct(iterable $forms)
    {
        Assert::allIsInstanceOf($forms, BatchActionFormInterface::class);

        $this->forms = $forms;
    }

    public function provide(string $type): ?string
    {
        foreach ($this->forms as $form) {
            if ($form->supported($type)) {
                return get_class($form);
            }
        }

        return null;
        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
