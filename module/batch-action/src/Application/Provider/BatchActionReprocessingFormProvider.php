<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Provider;

use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Application\Form\BatchActionReprocessingFormInterface;
use Ergonode\BatchAction\Application\Form\BatchActionReprocessForm;

class BatchActionReprocessingFormProvider
{
    /**
     * @var BatchActionReprocessingFormInterface[]
     */
    private iterable $forms;

    /**
     * @param BatchActionReprocessingFormInterface[] $forms
     */
    public function __construct(iterable $forms)
    {
        Assert::allIsInstanceOf($forms, BatchActionReprocessingFormInterface::class);

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

        return BatchActionReprocessForm::class;
    }
}
