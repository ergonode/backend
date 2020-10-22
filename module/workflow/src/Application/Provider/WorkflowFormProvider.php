<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Provider;

use Ergonode\Workflow\Application\Form\Workflow\WorkflowFormInterface;

class WorkflowFormProvider
{
    /**
     * @var WorkflowFormInterface[]
     */
    private array $forms;

    /**
     * @param WorkflowFormInterface ...$forms
     */
    public function __construct(WorkflowFormInterface ...$forms)
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

        throw new \RuntimeException(sprintf('Can\' find Workflow form for %s type', $type));
    }
}
